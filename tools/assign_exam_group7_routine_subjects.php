<?php

$mysqli = new mysqli('ims_db', 'ims_user', '4fc698bd98538ea150bb2ef9', 'bkbkrghs');
if ($mysqli->connect_errno) {
    fwrite(STDERR, "DB connection failed: " . $mysqli->connect_error . PHP_EOL);
    exit(1);
}
$mysqli->set_charset('utf8mb4');

$examIds = [
    6  => 3,
    7  => 5,
    8  => 6,
    9  => 7,
    10 => 8,
];

$subjectAliases = [
    'General Science' => 'General Science',
    'Bangladesh and Global Studies' => 'Bangladesh and Global Studies',
    'Business Entrepreneurship' => 'Business Entrepreneurship',
    'FINANCE AND BANKING' => 'FINANCE AND BANKING',
    'RELIGION AND MORAL EDUCATION' => 'RELIGION AND MORAL EDUCATION',
    'BANGLADESH HISTORY AND WORLD CIVILIZATION' => 'BANGLADESH HISTORY AND WORLD CIVILIZATION',
    'GEOGRAPHY AND ENVIRONMENT' => 'GEOGRAPHY AND ENVIRONMENT',
    'CIVICS' => 'CIVICS',
    'ECONOMICS' => 'ECONOMICS',
];

$schedule = [
    6 => [
        ['2026-07-02', '10:00:00', ['MATH']],
        ['2026-07-06', '13:00:00', ['ENGLISH 1']],
        ['2026-07-09', '10:00:00', ['ICT']],
        ['2026-07-12', '10:00:00', ['General Science']],
        ['2026-07-13', '13:00:00', ['BANGLA 1']],
        ['2026-07-14', '13:00:00', ['BANGLA 2']],
        ['2026-07-15', '13:00:00', ['RELIGION AND MORAL EDUCATION']],
        ['2026-07-16', '13:00:00', ['Bangladesh and Global Studies']],
    ],
    7 => [
        ['2026-07-01', '13:00:00', ['ENGLISH 1']],
        ['2026-07-02', '13:00:00', ['RELIGION AND MORAL EDUCATION']],
        ['2026-07-05', '13:00:00', ['ENGLISH 2']],
        ['2026-07-06', '10:00:00', ['BANGLA 1']],
        ['2026-07-07', '13:00:00', ['BANGLA 2']],
        ['2026-07-09', '13:00:00', ['General Science']],
        ['2026-07-12', '13:00:00', ['MATH']],
        ['2026-07-13', '13:00:00', ['ICT']],
        ['2026-07-16', '10:00:00', ['Bangladesh and Global Studies']],
    ],
    8 => [
        ['2026-07-01', '10:00:00', ['MATH']],
        ['2026-07-05', '10:00:00', ['ENGLISH 1']],
        ['2026-07-07', '10:00:00', ['ENGLISH 2']],
        ['2026-07-08', '10:00:00', ['RELIGION AND MORAL EDUCATION']],
        ['2026-07-09', '10:00:00', ['Bangladesh and Global Studies']],
        ['2026-07-13', '10:00:00', ['BANGLA 1']],
        ['2026-07-14', '10:00:00', ['BANGLA 2']],
        ['2026-07-15', '10:00:00', ['General Science']],
        ['2026-07-16', '10:00:00', ['ICT']],
    ],
    9 => [
        ['2026-07-01', '10:00:00', ['ENGLISH 1']],
        ['2026-07-02', '10:00:00', ['RELIGION AND MORAL EDUCATION']],
        ['2026-07-05', '10:00:00', ['ENGLISH 2']],
        ['2026-07-06', '10:00:00', ['BANGLA 1']],
        ['2026-07-07', '10:00:00', ['BANGLA 2']],
        ['2026-07-08', '10:00:00', ['General Science', 'Bangladesh and Global Studies']],
        ['2026-07-09', '10:00:00', ['ICT']],
        ['2026-07-12', '10:00:00', ['MATH']],
        ['2026-07-13', '10:00:00', ['PHYSICS', 'ACCOUNTING', 'BANGLADESH HISTORY AND WORLD CIVILIZATION']],
        ['2026-07-14', '10:00:00', ['BIOLOGY', 'Business Entrepreneurship', 'GEOGRAPHY AND ENVIRONMENT']],
        ['2026-07-15', '10:00:00', ['CHEMISTRY', 'FINANCE AND BANKING', 'CIVICS', 'ECONOMICS']],
        ['2026-07-16', '10:00:00', ['AGRICULTURE', 'HOME SCIENCE', 'HIGHER MATH']],
    ],
    10 => [
        ['2026-07-01', '13:00:00', ['BANGLA 1']],
        ['2026-07-02', '13:00:00', ['BANGLA 2']],
        ['2026-07-05', '13:00:00', ['MATH']],
        ['2026-07-06', '13:00:00', ['RELIGION AND MORAL EDUCATION']],
        ['2026-07-07', '13:00:00', ['PHYSICS', 'ACCOUNTING', 'BANGLADESH HISTORY AND WORLD CIVILIZATION']],
        ['2026-07-08', '13:00:00', ['BIOLOGY', 'Business Entrepreneurship', 'GEOGRAPHY AND ENVIRONMENT']],
        ['2026-07-09', '13:00:00', ['CHEMISTRY', 'FINANCE AND BANKING', 'CIVICS', 'ECONOMICS']],
        ['2026-07-12', '13:00:00', ['ENGLISH 1']],
        ['2026-07-13', '13:00:00', ['ICT']],
        ['2026-07-14', '13:00:00', ['ENGLISH 2']],
        ['2026-07-15', '13:00:00', ['General Science', 'Bangladesh and Global Studies']],
        ['2026-07-16', '13:00:00', ['AGRICULTURE', 'HOME SCIENCE', 'HIGHER MATH']],
    ],
];

function query_one(mysqli $mysqli, string $sql, string $types = '', array $params = [])
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

function execute_stmt(mysqli $mysqli, string $sql, string $types = '', array $params = []): int
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

function subject_id(mysqli $mysqli, string $name, array $subjectAliases, array &$created): int
{
    $canonical = $subjectAliases[$name] ?? $name;
    $row = query_one($mysqli, 'SELECT id FROM subjects WHERE LOWER(name) = LOWER(?) LIMIT 1', 's', [$canonical]);
    if ($row) {
        return (int) $row['id'];
    }

    execute_stmt($mysqli, 'INSERT INTO subjects (name, code, type, is_active) VALUES (?, ?, ?, ?)', 'ssss', [$canonical, '', 'theory', 'yes']);
    $id = (int) $mysqli->insert_id;
    $created[] = $canonical;
    return $id;
}

$createdSubjects = [];
$insertedSubjects = 0;
$mysqli->begin_transaction();

try {
    $placeholders = implode(',', array_fill(0, count($examIds), '?'));
    execute_stmt(
        $mysqli,
        "DELETE FROM exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exams_id IN ($placeholders)",
        str_repeat('i', count($examIds)),
        array_values($examIds)
    );

    $insertSql = 'INSERT INTO exam_group_class_batch_exam_subjects
        (exam_group_class_batch_exams_id, subject_id, date_from, time_from, duration, room_no, max_marks, min_marks, credit_hours, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

    foreach ($schedule as $class => $rows) {
        $examId = $examIds[$class];
        foreach ($rows as [$date, $time, $subjects]) {
            foreach ($subjects as $subjectName) {
                $subjectId = subject_id($mysqli, $subjectName, $subjectAliases, $createdSubjects);
                $duration = '3';
                $room = '';
                $maxMarks = 100.00;
                $minMarks = 33.00;
                $creditHours = 0.00;
                $isActive = 0;
                execute_stmt(
                    $mysqli,
                    $insertSql,
                    'iissssdddi',
                    [$examId, $subjectId, $date, $time, $duration, $room, $maxMarks, $minMarks, $creditHours, $isActive]
                );
                $insertedSubjects++;
            }
        }
    }

    foreach ($examIds as $class => $examId) {
        $dates = query_one(
            $mysqli,
            'SELECT MIN(date_from) AS date_from, MAX(date_from) AS date_to FROM exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exams_id = ?',
            'i',
            [$examId]
        );
        execute_stmt(
            $mysqli,
            'UPDATE exam_group_class_batch_exams SET date_from = ?, date_to = ? WHERE id = ?',
            'ssi',
            [$dates['date_from'], $dates['date_to'], $examId]
        );
    }

    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}

echo 'Created subjects: ' . (empty($createdSubjects) ? 'none' : implode(', ', $createdSubjects)) . PHP_EOL;
echo 'Inserted exam subject rows: ' . $insertedSubjects . PHP_EOL;
