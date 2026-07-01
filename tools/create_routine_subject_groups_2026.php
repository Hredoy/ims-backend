<?php

$mysqli = new mysqli('ims_db', 'ims_user', '4fc698bd98538ea150bb2ef9', 'bkbkrghs');
if ($mysqli->connect_errno) {
    fwrite(STDERR, "DB connection failed: " . $mysqli->connect_error . PHP_EOL);
    exit(1);
}
$mysqli->set_charset('utf8mb4');

$sessionId = 22;

$groups = [
    'CLASS SIX ROUTINE 2026' => [
        'class_id' => 2,
        'sections' => ['A', 'B'],
        'subjects' => ['MATH', 'ENGLISH 1', 'ICT', 'General Science', 'BANGLA 1', 'BANGLA 2', 'RELIGION AND MORAL EDUCATION', 'Bangladesh and Global Studies'],
    ],
    'CLASS SEVEN ROUTINE 2026' => [
        'class_id' => 3,
        'sections' => ['1'],
        'subjects' => ['ENGLISH 1', 'RELIGION AND MORAL EDUCATION', 'ENGLISH 2', 'BANGLA 1', 'BANGLA 2', 'General Science', 'MATH', 'ICT', 'Bangladesh and Global Studies'],
    ],
    'CLASS EIGHT ROUTINE 2026' => [
        'class_id' => 4,
        'sections' => ['1'],
        'subjects' => ['MATH', 'ENGLISH 1', 'ENGLISH 2', 'RELIGION AND MORAL EDUCATION', 'Bangladesh and Global Studies', 'BANGLA 1', 'BANGLA 2', 'General Science', 'ICT'],
    ],
    'CLASS NINE ROUTINE 2026' => [
        'class_id' => 5,
        'sections' => ['Science', 'Commerce', 'Humanities'],
        'subjects' => [
            'ENGLISH 1', 'RELIGION AND MORAL EDUCATION', 'ENGLISH 2', 'BANGLA 1', 'BANGLA 2',
            'General Science', 'Bangladesh and Global Studies', 'ICT', 'MATH',
            'PHYSICS', 'ACCOUNTING', 'BANGLADESH HISTORY AND WORLD CIVILIZATION',
            'BIOLOGY', 'Business Entrepreneurship', 'GEOGRAPHY AND ENVIRONMENT',
            'CHEMISTRY', 'FINANCE AND BANKING', 'CIVICS', 'ECONOMICS',
            'AGRICULTURE', 'HOME SCIENCE', 'HIGHER MATH',
        ],
    ],
    'CLASS TEN ROUTINE 2026' => [
        'class_id' => 6,
        'sections' => ['Science', 'Commerce', 'Humanities'],
        'subjects' => [
            'BANGLA 1', 'BANGLA 2', 'MATH', 'RELIGION AND MORAL EDUCATION',
            'PHYSICS', 'ACCOUNTING', 'BANGLADESH HISTORY AND WORLD CIVILIZATION',
            'BIOLOGY', 'Business Entrepreneurship', 'GEOGRAPHY AND ENVIRONMENT',
            'CHEMISTRY', 'FINANCE AND BANKING', 'CIVICS', 'ECONOMICS',
            'ENGLISH 1', 'ICT', 'ENGLISH 2', 'General Science', 'Bangladesh and Global Studies',
            'AGRICULTURE', 'HOME SCIENCE', 'HIGHER MATH',
        ],
    ],
];

function one(mysqli $mysqli, string $sql, string $types = '', array $params = [])
{
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException($mysqli->error);
    }
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function exec_stmt(mysqli $mysqli, string $sql, string $types = '', array $params = []): int
{
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException($mysqli->error);
    }
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->affected_rows;
}

function subject_id(mysqli $mysqli, string $name): int
{
    $row = one($mysqli, 'SELECT id FROM subjects WHERE LOWER(name) = LOWER(?) LIMIT 1', 's', [$name]);
    if (!$row) {
        throw new RuntimeException("Missing subject: {$name}");
    }
    return (int) $row['id'];
}

function class_section_id(mysqli $mysqli, int $classId, string $section): int
{
    $row = one(
        $mysqli,
        'SELECT class_sections.id FROM class_sections JOIN sections ON sections.id = class_sections.section_id WHERE class_sections.class_id = ? AND sections.section = ? LIMIT 1',
        'is',
        [$classId, $section]
    );
    if (!$row) {
        throw new RuntimeException("Missing class section: class {$classId}, section {$section}");
    }
    return (int) $row['id'];
}

$created = [];
$updated = [];
$mysqli->begin_transaction();

try {
    foreach ($groups as $groupName => $group) {
        $row = one($mysqli, 'SELECT id FROM subject_groups WHERE name = ? AND session_id = ? LIMIT 1', 'si', [$groupName, $sessionId]);
        if ($row) {
            $groupId = (int) $row['id'];
            exec_stmt($mysqli, 'UPDATE subject_groups SET description = ? WHERE id = ?', 'si', ['Created from Mid Term Exam-2026 routine', $groupId]);
            exec_stmt($mysqli, 'DELETE FROM subject_group_subjects WHERE subject_group_id = ? AND session_id = ?', 'ii', [$groupId, $sessionId]);
            exec_stmt($mysqli, 'DELETE FROM subject_group_class_sections WHERE subject_group_id = ? AND session_id = ?', 'ii', [$groupId, $sessionId]);
            $updated[] = $groupName;
        } else {
            exec_stmt(
                $mysqli,
                'INSERT INTO subject_groups (name, description, session_id) VALUES (?, ?, ?)',
                'ssi',
                [$groupName, 'Created from Mid Term Exam-2026 routine', $sessionId]
            );
            $groupId = (int) $mysqli->insert_id;
            $created[] = $groupName;
        }

        foreach ($group['subjects'] as $subjectName) {
            $subjectId = subject_id($mysqli, $subjectName);
            exec_stmt(
                $mysqli,
                'INSERT INTO subject_group_subjects (subject_group_id, session_id, subject_id) VALUES (?, ?, ?)',
                'iii',
                [$groupId, $sessionId, $subjectId]
            );
        }

        foreach ($group['sections'] as $section) {
            $classSectionId = class_section_id($mysqli, $group['class_id'], $section);
            exec_stmt(
                $mysqli,
                'DELETE FROM subject_group_class_sections WHERE class_section_id = ? AND session_id = ? AND subject_group_id <> ?',
                'iii',
                [$classSectionId, $sessionId, $groupId]
            );
            exec_stmt(
                $mysqli,
                'INSERT INTO subject_group_class_sections (subject_group_id, class_section_id, session_id, description, is_active) VALUES (?, ?, ?, ?, ?)',
                'iiisi',
                [$groupId, $classSectionId, $sessionId, 'Created from Mid Term Exam-2026 routine', 0]
            );
        }
    }

    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}

echo 'Created groups: ' . (empty($created) ? 'none' : implode(', ', $created)) . PHP_EOL;
echo 'Updated groups: ' . (empty($updated) ? 'none' : implode(', ', $updated)) . PHP_EOL;
