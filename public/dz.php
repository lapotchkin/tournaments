<?php
$user = 'root';
$pass = 'kfgjxrby';
$db = 'tournament';

try {
    //Соединение с БД
    $dbh = new PDO("mysql:host=localhost;dbname={$db}", $user, $pass);
    //Старт транзакции
    $dbh->beginTransaction();

    $records = [
        ['surname' => 'Иванов', 'age' => '12', 'work_place' => 'Не работает'],
        ['surname' => 'Петров', 'age' => '65', 'work_place' => 'На пенсии'],
        ['surname' => 'Сидоров', 'age' => '36', 'work_place' => 'Крутой программист'],
    ];

    //Добавляем новые записи
    foreach ($records as $record) {
        $stmt = $dbh->prepare("INSERT INTO anketa1 (surname, age, work_place) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $record['surname']);
        $stmt->bindParam(2, $record['age'], PDO::PARAM_INT);
        $stmt->bindParam(3, $record['work_place']);
        $stmt->execute();
    }

    //Фиксируем транзакцию
    $dbh->commit();

    //Вывод
    ?>
    <html>
    <body>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Возраст</th>
            <th>Место работы</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dbh->query('SELECT * from anketa1') as $row): ?>
        <tr>
            <td><?= $row['num_id'] ?></td>
            <td><?= $row['surname'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['work_place'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </body>
    </html>

    <?php
    $dbh = null;
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
