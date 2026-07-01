<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = new mysqli('db', 'ims_user', '4fc698bd98538ea150bb2ef9', 'bkbkrghs');
$db->set_charset('utf8');

$sessionId = (int) $db->query('SELECT session_id FROM sch_settings WHERE id = 1')->fetch_assoc()['session_id'];

$imports = [
    ['file' => 'class6.csv', 'prefix' => 'C6A', 'class_id' => 2, 'section_id' => 3, 'label' => 'Class 6 / A'],
    ['file' => 'class7.csv', 'prefix' => 'C7S1', 'class_id' => 3, 'section_id' => 2, 'label' => 'Class 7 / 1'],
    ['file' => 'class8.csv', 'prefix' => 'C8S1', 'class_id' => 4, 'section_id' => 2, 'label' => 'Class 8 / 1'],
    ['file' => 'class9-science.csv', 'prefix' => 'C9SCI', 'class_id' => 5, 'section_id' => 5, 'label' => 'Class 9 / Science'],
    ['file' => 'class9-commerce.csv', 'prefix' => 'C9COM', 'class_id' => 5, 'section_id' => 6, 'label' => 'Class 9 / Commerce'],
    ['file' => 'class9-arts.csv', 'prefix' => 'C9HUM', 'class_id' => 5, 'section_id' => 7, 'label' => 'Class 9 / Humanities'],
    ['file' => 'class10-science.csv', 'prefix' => 'C10SCI', 'class_id' => 6, 'section_id' => 5, 'label' => 'Class 10 / Science'],
    ['file' => 'class10-commerce.csv', 'prefix' => 'C10COM', 'class_id' => 6, 'section_id' => 6, 'label' => 'Class 10 / Commerce'],
    ['file' => 'class10-arts.csv', 'prefix' => 'C10HUM', 'class_id' => 6, 'section_id' => 7, 'label' => 'Class 10 / Humanities'],
];

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

function csv_rows(string $path): Generator
{
    $handle = fopen($path, 'rb');
    if ($handle === false) {
        throw new RuntimeException("Unable to open $path");
    }

    $headers = fgetcsv($handle);
    if ($headers === false) {
        fclose($handle);
        return;
    }

    if (isset($headers[0])) {
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
    }

    while (($row = fgetcsv($handle)) !== false) {
        if (count(array_filter($row, static fn ($value) => trim((string) $value) !== '')) === 0) {
            continue;
        }

        $assoc = [];
        foreach ($headers as $index => $header) {
            $assoc[trim((string) $header)] = $row[$index] ?? '';
        }
        yield $assoc;
    }

    fclose($handle);
}

function clean(?string $value): string
{
    return trim((string) $value);
}

function date_or_null(?string $value): ?string
{
    $value = clean($value);
    if ($value === '') {
        return null;
    }

    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value ? $value : null;
}

function not_null_string(?string $value): string
{
    return clean($value);
}

function prefixed_admission(string $prefix, int $rowNumber): string
{
    return $prefix . '-' . str_pad((string) $rowNumber, 3, '0', STR_PAD_LEFT);
}

function exists_admission(mysqli $db, string $admissionNo): bool
{
    $stmt = $db->prepare('SELECT id FROM students WHERE admission_no = ? LIMIT 1');
    $stmt->bind_param('s', $admissionNo);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
}

function insert_assoc(mysqli $db, string $table, array $data): int
{
    $columns = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $sql = sprintf(
        'INSERT INTO `%s` (`%s`) VALUES (%s)',
        $table,
        implode('`, `', $columns),
        $placeholders
    );
    $stmt = $db->prepare($sql);
    $types = str_repeat('s', count($data));
    $values = array_values($data);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();

    return $id;
}

$summary = [];

$db->begin_transaction();

try {
    foreach ($imports as $import) {
        $path = __DIR__ . '/../import_data/' . $import['file'];
        $inserted = 0;
        $skipped = 0;
        $rowNumber = 0;

        foreach (csv_rows($path) as $row) {
            $rowNumber++;
            $admissionNo = prefixed_admission($import['prefix'], $rowNumber);

            if (exists_admission($db, $admissionNo)) {
                $skipped++;
                continue;
            }

            $mapped = [];
            foreach ($columns as $csvColumn => $studentColumn) {
                $mapped[$studentColumn] = clean($row[$csvColumn] ?? '');
            }

            $mapped['admission_no'] = $admissionNo;
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
                'blood_group' => not_null_string($mapped['blood_group']),
                'vehroute_id' => 0,
                'hostel_room_id' => 0,
                'guardian_is' => clean($mapped['guardian_is']) === '' ? 'father' : strtolower($mapped['guardian_is']),
                'guardian_occupation' => not_null_string($mapped['guardian_occupation']),
                'father_pic' => '',
                'mother_pic' => '',
                'guardian_pic' => '',
                'is_active' => 'yes',
                'height' => not_null_string($mapped['height']),
                'weight' => not_null_string($mapped['weight']),
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
                'class_id' => $import['class_id'],
                'section_id' => $import['section_id'],
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

            $inserted++;
        }

        $summary[] = [
            'label' => $import['label'],
            'file' => $import['file'],
            'inserted' => $inserted,
            'skipped' => $skipped,
        ];
    }

    $db->commit();
} catch (Throwable $e) {
    $db->rollback();
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}

echo "Session ID: {$sessionId}\n";
foreach ($summary as $line) {
    echo "{$line['label']} ({$line['file']}): inserted {$line['inserted']}, skipped {$line['skipped']}\n";
}
