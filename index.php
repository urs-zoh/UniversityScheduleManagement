<?php
// Password for authorization
define('PASSWORD', 'correctpassword');

class AcademicGroup
{
    public $groupName;
    public $specialty;

    public function __construct($groupName, $specialty)
    {
        $this->groupName = $groupName;
        $this->specialty = $specialty;
    }
}

class Specialty
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

class Discipline
{
    public $name;
    public $teacher;

    public function __construct($name, $teacher)
    {
        $this->name = $name;
        $this->teacher = $teacher;
    }
}

class Classroom
{
    public $roomNumber;
    public $capacity;

    public function __construct($roomNumber, $capacity)
    {
        $this->roomNumber = $roomNumber;
        $this->capacity = $capacity;
    }
}

class Teacher
{
    public $firstName;
    public $lastName;
    public $specialty;

    public function __construct($firstName, $lastName, $specialty)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->specialty = $specialty;
    }
}

class Schedule
{
    public $group;
    public $discipline;
    public $classroom;
    public $time;

    public function __construct($group, $discipline, $classroom, $time)
    {
        $this->group = $group;
        $this->discipline = $discipline;
        $this->classroom = $classroom;
        $this->time = $time;
    }
}

// Initial data
$specialties = [new Specialty("Computer Science"), new Specialty("Mathematics")];
$teachers = [
    new Teacher("John", "Doe", $specialties[0]),
    new Teacher("Jane", "Smith", $specialties[1])
];
$classrooms = [new Classroom("101", 30), new Classroom("102", 25)];
$academicGroups = [new AcademicGroup("Group A", $specialties[0]), new AcademicGroup("Group B", $specialties[1])];
$schedules = [];

// Submition form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password'] === PASSWORD) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $group = $academicGroups[$_POST['group']];
                $discipline = new Discipline($_POST['discipline'], $teachers[$_POST['teacher']]);
                $classroom = $classrooms[$_POST['classroom']];
                $time = $_POST['time'];
                $schedules[] = new Schedule($group, $discipline, $classroom, $time);
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Schedule Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">University Schedule Management</h1>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Login</h5>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Enter Password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Login</button>
                </form>
            </div>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password'] === PASSWORD): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Add New Schedule</h5>
                <form method="POST" action="">
                    <input type="hidden" name="password" value="<?php echo PASSWORD; ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="group">Group</label>
                        <select name="group" id="group" class="form-control" required>
                            <?php foreach ($academicGroups as $index => $group): ?>
                            <option value="<?php echo $index; ?>"><?php echo $group->groupName; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discipline">Discipline</label>
                        <input type="text" name="discipline" id="discipline" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="teacher">Teacher</label>
                        <select name="teacher" id="teacher" class="form-control" required>
                            <?php foreach ($teachers as $index => $teacher): ?>
                            <option value="<?php echo $index; ?>">
                                <?php echo $teacher->firstName . " " . $teacher->lastName; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="classroom">Classroom</label>
                        <select name="classroom" id="classroom" class="form-control" required>
                            <?php foreach ($classrooms as $index => $classroom): ?>
                            <option value="<?php echo $index; ?>"><?php echo $classroom->roomNumber; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="text" name="time" id="time" class="form-control"
                            placeholder="e.g., 10:00 - 11:30 AM" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Add Schedule</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Schedule</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Specialty</th>
                            <th>Discipline</th>
                            <th>Teacher</th>
                            <th>Classroom</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($schedule->group->groupName); ?></td>
                            <td><?php echo htmlspecialchars($schedule->group->specialty->name); ?></td>
                            <td><?php echo htmlspecialchars($schedule->discipline->name); ?></td>
                            <td><?php echo htmlspecialchars($schedule->discipline->teacher->firstName . " " . $schedule->discipline->teacher->lastName); ?>
                            </td>
                            <td><?php echo htmlspecialchars($schedule->classroom->roomNumber); ?></td>
                            <td><?php echo htmlspecialchars($schedule->time); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>