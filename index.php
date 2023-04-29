<!DOCTYPE html>
<html>

<head> </head>

<body>

    <?php
    foreach (glob("./AgeGrade/*.php") as $filename) {
        include $filename;
    }
    include './AgeGrade/Table/AbstractTable.php';
    include './AgeGrade/Table/MaleTable.php';
    include './AgeGrade/Table/FemaleTable.php';

    use Runalyze\AgeGrade\Lookup;
    use Runalyze\AgeGrade\Table\MaleTable;
    use Runalyze\AgeGrade\Table\FemaleTable;

    $display_table = '';
    $gender = '';
    if (isset($_POST['upload']) && $_POST['upload'] == 'Upload CSV') {
        $upload_dir = getcwd() . DIRECTORY_SEPARATOR . '/uploads';
        if ($_FILES['csv']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['csv']['tmp_name'];
            $name = basename($_FILES['csv']['tmp_name']);
            $csv_file = $upload_dir . '/' . $name;
            move_uploaded_file($tmp_name, $csv_file);
            $display_table = get_file($csv_file);
        }
    }

    ?>
    <form action="" , method="post" , enctype="multipart/form-data">
        <input type="file" name="csv" />
        <input type="submit" name="upload" value="Upload CSV" />
    </form>

    <div>
        <?php
        echo $display_table;
        ?>
    </div>


    <?php

    class Athlete
    {
        public $lname;
        public $fname;
        public $distance;
        public $mark;
        public $age;
        public $sex;
        function __construct(string $lname, string $fname, float $distance, int $mark, int $age, string $sex)
        {
            $this->lname = $lname;
            $this->fname = $fname;
            $this->distance = floatval($distance);
            $this->mark = floatval($mark);
            $this->age = $age;
            $this->sex = $sex;
        }
    }

    function get_file($csv_file)
    {
        $html = '<table>';
        $file = fopen($csv_file, 'r');
        $header_arr = fgetcsv($file);
        $html .= '<thead>';
        foreach ($header_arr as $key => $value) {
            $html .= '<th>' . $value . '</th>';
        }
        $html .= '<th>' . 'AgeGrade' . '</th>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $rows = 0;
        $people = array();
        while ($line = fgetcsv($file)) {
            $column_count = count($line);
            $html .= '<tr>';
            for ($i = 0; $i < $column_count; $i++) {
                $html .= '<td>' . $line[$i] . '</td>';
            }
            $people[$rows] = new Athlete($line[0], $line[1], $line[2], $line[3], $line[4], $line[5]);
            if (strcmp($people[$rows]->sex, 'M')) {
                $Lookup = new Lookup(new MaleTable(), $people[$rows]->age);
            } else if (strcmp($people[$rows]->sex, 'F')) {
                $Lookup = new Lookup(new FemaleTable(), $people[$rows]->age);
            }
            $html .= '<td>' . $Lookup->getAgeGrade($people[$rows]->distance, $people[$rows]->mark) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        fclose($file);
        return $html;
    }
    ?>


</body>

</html>
