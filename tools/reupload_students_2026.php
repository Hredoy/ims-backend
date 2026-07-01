<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$jsonPath = __DIR__ . '/../import_data/student-list-2026.json';
if (!is_file($jsonPath)) {
    fwrite(STDERR, "Prepared JSON not found: {$jsonPath}\n");
    exit(1);
}

$payload = json_decode(file_get_contents($jsonPath), true);
if (!is_array($payload) || empty($payload['students'])) {
    fwrite(STDERR, "Prepared JSON has no students.\n");
    exit(1);
}

$db = new mysqli('db', 'ims_user', '4fc698bd98538ea150bb2ef9', 'bkbkrghs');
$db->set_charset('utf8');

$sessionId = (int) $db->query('SELECT session_id FROM sch_settings WHERE id = 1')->fetch_assoc()['session_id'];

$columns = [
    'admission_no' => 'admission_no',
    'roll_no' => 'roll_no',
    'first_name' => 'firstname',
    'middlename' => 'middlename',
    'last_name' => 'lastname',
    'gender' => 'gender',
    'date_of_birth' => 'dob',
    'category' => 'category_id',
    'religion' => 'religion',
    'caste' => 'cast',
    'mobile_no' => 'mobileno',
    'email' => 'email',
    'admission_date' => 'admission_date',
    'blood_group' => 'blood_group',
    'student_house' => 'school_house_id',
    'height' => 'height',
    'weight' => 'weight',
    'measurement_date' => 'measurement_date',
    'father_name' => 'father_name',
    'father_phone' => 'father_phone',
    'father_occupation' => 'father_occupation',
    'mother_name' => 'mother_name',
    'mother_phone' => 'mother_phone',
    'mother_occupation' => 'mother_occupation',
    'guardian_is' => 'guardian_is',
    'guardian_name' => 'guardian_name',
    'guardian_relation' => 'guardian_relation',
    'guardian_email' => 'guardian_email',
    'guardian_phone' => 'guardian_phone',
    'guardian_occupation' => 'guardian_occupation',
    'guardian_address' => 'guardian_address',
    'current_address' => 'current_address',
    'permanent_address' => 'permanent_address',
    'bank_account_no' => 'bank_account_no',
    'bank_name' => 'bank_name',
    'ifsc_code' => 'ifsc_code',
    'national_identification_no' => 'adhar_no',
    'local_identification_no' => 'samagra_id',
    'rte' => 'rte',
    'previous_school' => 'previous_school',
    'note' => 'note',
];

$studentColumns = [
    'parent_id', 'admission_no', 'roll_no', 'admission_date', 'firstname', 'middlename', 'lastname',
    'rte', 'image', 'mobileno', 'email', 'religion', 'cast', 'dob', 'gender', 'current_address',
    'permanent_address', 'category_id', 'route_id', 'school_house_id', 'blood_group', 'vehroute_id',
    'hostel_room_id', 'adhar_no', 'samagra_id', 'bank_account_no', 'bank_name', 'ifsc_code',
    'guardian_is', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone',
    'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_phone', 'guardian_occupation',
    'guardian_address', 'guardian_email', 'father_pic', 'mother_pic', 'guardian_pic', 'is_active',
    'previous_school', 'height', 'weight', 'measurement_date', 'dis_reason', 'note', 'dis_note',
    'disable_at',
];

function clean($value): string
{
    return trim((string) ($value ?? ''));
}

function date_or_null($value): ?string
{
    $value = clean($value);
    if ($value === '') {
        return null;
    }

    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value ? $value : null;
}

function insert_assoc(mysqli $db, string $table, array $data): int
{
    $columns = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $sql = sprintf('INSERT INTO `%s` (`%s`) VALUES (%s)', $table, implode('`, `', $columns), $placeholders);
    $stmt = $db->prepare($sql);
    $types = str_repeat('s', count($data));
    $values = array_values($data);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();

    return $id;
}

function purge_students(mysqli $db): void
{
    $db->query("DELETE FROM users WHERE role IN ('student', 'parent')");
    $db->query('DELETE FROM students');
}

$summary = [];
foreach ($payload['summary'] as $item) {
    $summary[$item['label']] = 0;
}

$db->begin_transaction();

try {
    echo "Step 1: Removing existing student and parent login records...\n";
    purge_students($db);

    echo "Step 2: Uploading workbook rows into session {$sessionId}...\n";
    foreach ($payload['students'] as $source) {
        $mapped = [];
        foreach ($columns as $jsonColumn => $studentColumn) {
            $mapped[$studentColumn] = clean($source[$jsonColumn] ?? '');
        }

        $mapped['dob'] = date_or_null($mapped['dob']);
        $mapped['admission_date'] = date_or_null($mapped['admission_date']);
        $mapped['measurement_date'] = date_or_null($mapped['measurement_date']) ?? '0000-00-00';
        $mapped['image'] = strtolower($mapped['gender']) === 'female'
            ? 'uploads/student_images/default_female.jpg'
            : 'uploads/student_images/default_male.jpg';

        $defaults = [
            'parent_id' => 0,
            'route_id' => 0,
            'school_house_id' => clean($mapped['school_house_id']) === '' ? 0 : $mapped['school_house_id'],
            'blood_group' => clean($mapped['blood_group']),
            'vehroute_id' => 0,
            'hostel_room_id' => 0,
            'guardian_is' => clean($mapped['guardian_is']) === '' ? 'father' : strtolower($mapped['guardian_is']),
            'guardian_occupation' => clean($mapped['guardian_occupation']),
            'father_pic' => '',
            'mother_pic' => '',
            'guardian_pic' => '',
            'is_active' => 'yes',
            'height' => clean($mapped['height']),
            'weight' => clean($mapped['weight']),
            'dis_reason' => 0,
            'dis_note' => '',
            'disable_at' => '0000-00-00',
        ];

        $student = array_merge($mapped, $defaults);
        $student = array_intersect_key($student, array_flip($studentColumns));
        $student = array_replace(array_fill_keys($studentColumns, ''), $student);
        $studentId = insert_assoc($db, 'students', $student);

        insert_assoc($db, 'student_session', [
            'session_id' => $sessionId,
            'student_id' => $studentId,
            'class_id' => (int) $source['class_id'],
            'section_id' => (int) $source['section_id'],
            'route_id' => 0,
            'hostel_room_id' => 0,
            'vehroute_id' => null,
            'transport_fees' => '0.00',
            'fees_discount' => '0.00',
            'is_active' => 'no',
            'is_alumni' => 0,
            'default_login' => 0,
        ]);

        insert_assoc($db, 'users', [
            'user_id' => $studentId,
            'username' => 'std' . $studentId,
            'password' => 'password',
            'childs' => '',
            'role' => 'student',
            'verification_code' => '',
            'lang_id' => 0,
            'is_active' => 'yes',
        ]);

        $parentUserId = insert_assoc($db, 'users', [
            'user_id' => $studentId,
            'username' => 'parent' . $studentId,
            'password' => 'password',
            'childs' => (string) $studentId,
            'role' => 'parent',
            'verification_code' => '',
            'lang_id' => 0,
            'is_active' => 'yes',
        ]);

        $stmt = $db->prepare('UPDATE students SET parent_id = ? WHERE id = ?');
        $stmt->bind_param('ii', $parentUserId, $studentId);
        $stmt->execute();
        $stmt->close();

        $summary[$source['label']]++;
    }

    $db->commit();
} catch (Throwable $e) {
    $db->rollback();
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}

echo "Step 3: Completed upload.\n";
foreach ($summary as $label => $count) {
    echo "{$label}: {$count}\n";
}
echo 'Total: ' . array_sum($summary) . "\n";

