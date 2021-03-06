<?php

/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/23/2017
 * Time: 9:54 AM
 */
class DashboardController
{

    public function norecords()
    {
        echo "<h1>You currently have no records.</h1>";

    }

    public function getDOB($db_handle)
    {
        // check email exist or not
        $query = "SELECT month,day,year FROM user_address WHERE user_account_id='" . $_SESSION['userID'] . "'";
        $result = $db_handle->runQuery($query);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $dob = $row['year'] . "/" . $row['month'] . "/" . $row['day'];
        }
        $result->close();

        return $dob | null;
    }

    public function showrecords($db_handle, $result)
    {

        // $highTypes[] = array();

        $dob = $this->getDOB($db_handle);

        if ((mysqli_num_rows($result) == 0)) {

            $this->norecords();
        } else {

            echo "<p>Click a date for a detail view.</p>";


            while ($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            $rowCount = 0;

            foreach ($rows as $row) {

                $colCount = 0;
                $collapse = "collapse" . $rowCount;
                ?>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title ">
                            <?= $rowCount + 1 ?>.
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#<?= $collapse ?>"><?= $row['date'] ?></a>

                            <div style="float:right;">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-default btn-sm active"
                                   role="button">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-default btn-sm active"
                                   role="button"
                                   onclick="return confirm('Are you sure you want to delete this item?\nIt cannot be undone.');">Delete</a>
                            </div>

                        </div>
                    </div>

                    <div id="<?= $collapse ?>" class="panel-collapse collapse ">


                        <div class="well">

                            <div class="row alert">

                                <?php
                                $diff = ($row['date'] - date('Y', strtotime($dob)));
                                ?>

                                <div style="float:left; margin-right:10px;">
                                    <h4>Age
                                        <button class="data-default" disabled="disabled"><?= $diff ?></button>
                                    </h4>
                                </div>
                                <div>

                                    <h4>Weight
                                        <button class="data-default" disabled="disabled"><?= $row['weight'] ?></button>
                                    </h4>

                                </div>


                                <table style="background-color: #fff;" class="table table-hover table-bordered">
                                    <tr>
                                        <th>HEALTH TYPE</th>
                                        <th>LEVEL</th>
                                        <th>CONDITION</th>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type2']) ?></td>
                                        <td><?= $row['health_type2_level'] ?> mmol/L</td>

                                        <?php
                                        if ($row['health_type2_level'] >= 50 && $row['health_type2_level'] <= 99)
                                            echo "<td class='success'>" . strtoupper("Ideal");
                                        else if ($row['health_type2_level'] >= 100 && $row['health_type2_level'] <= 129)
                                            echo "<td>" . strtoupper("Close to Ideal");
                                        else if ($row['health_type2_level'] >= 130 && $row['health_type2_level'] <= 159)
                                            echo "<td class='info'>" . strtoupper("Borderline-high");
                                        else if ($row['health_type2_level'] >= 160 && $row['health_type2_level'] <= 189)
                                            echo "<td class='warning'>" . strtoupper("High");
                                        else if ($row['health_type2_level'] >= 190 && $row['health_type2_level'] <= 300) {
                                            echo "<td class='danger'>" . strtoupper("Very High");
                                            $highTypes[$rowCount][$colCount] = ($row['health_type2']);
                                            $colCount++;
                                        }
                                        echo "</td>";
                                        ?>

                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type3']) ?></td>
                                        <td><?= $row['health_type3_level'] ?> mmol/L</td>
                                        <?php
                                        if ($row['health_type3_level'] >= 20 && $row['health_type3_level'] <= 39) {
                                            $highTypes[$rowCount][$colCount] = ($row['health_type3']);
                                            $colCount++;
                                            echo "<td class='danger'>" . strtoupper("Low (high heart disease risk)");

                                        } else if ($row['health_type3_level'] >= 40 && $row['health_type3_level'] <= 59)
                                            echo "<td class='warning'>" . strtoupper("Normal (but the higher the better)");
                                        else if ($row['health_type3_level'] >= 60 && $row['health_type3_level'] <= 90)
                                            echo "<td class='success'>" . strtoupper("Best (offers protection against heart disease)");
                                        echo "</td>";
                                        ?>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type1']) ?></td>
                                        <td><?= $row['health_type1_level'] ?> mmol/L</td>
                                        <?php
                                        if ($row['health_type1_level'] >= 0 && $row['health_type1_level'] <= 149)
                                            echo "<td class='success'>" . strtoupper("Normal");
                                        else if ($row['health_type1_level'] >= 150 && $row['health_type1_level'] <= 199)
                                            echo "<td class='info'>" . strtoupper("Borderline-high");
                                        else if ($row['health_type1_level'] >= 200 && $row['health_type1_level'] <= 499)
                                            echo "<td class='warning'>" . strtoupper("High");
                                        else {
                                            echo "<td class='danger'>" . strtoupper("Very High");
                                            $highTypes[$rowCount][$colCount] = ($row['health_type1']);
                                            $colCount++;
                                        }
                                        echo "</td>";
                                        ?>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type4']) ?></td>
                                        <td><?= $row['health_type4_level'] ?> mmol/L</td>
                                        <?php
                                        if ($row['health_type4_level'] >= 80 && $row['health_type4_level'] <= 200)
                                            echo "<td class='success'>" . strtoupper("Ideal");
                                        else if ($row['health_type4_level'] >= 201 && $row['health_type4_level'] <= 239)
                                            echo "<td class='warning'>" . strtoupper("Borderline-high");
                                        else if ($row['health_type4_level'] >= 240 && $row['health_type4_level'] <= 500) {
                                            echo "<td class='danger'>" . strtoupper("High");
                                            $highTypes[$rowCount][$colCount] = ($row['health_type4']);
                                            $colCount++;
                                        }
                                        echo "</td>";
                                        ?>
                                    </tr>
                                </table>

                            </div>

                            <div class="panel-footer"><p><span style="color:#000;"><b>Recommendation</b></span>:
                                    <?php

                                    if ($colCount == 0) {
                                        $strMsg[$rowCount] = "Your health is excellent!  Keep doing what you're doing.";
                                    } else {
                                        $strMsg[$rowCount] = "Your health is at risk.  Your ";
                                        for ($i = 0; $i < $colCount; $i++) {
                                            $strMsg[$rowCount] .= $highTypes[$rowCount][$i];
                                            if ($colCount == 2) {
                                                $strMsg[$rowCount] .= " and ";
                                            } else if ($colCount > 2) {
                                                if (($i + 1) < $colCount) {
                                                    $strMsg[$rowCount] .= ", ";
                                                } else {
                                                    $strMsg[$rowCount] .= " and ";
                                                }
                                            }
                                        }

                                        if ($colCount > 1) {
                                            $strMsg[$rowCount] .= " are too high or too low.";
                                        } else {
                                            $strMsg[$rowCount] .= " is too high or too low.";
                                        }

                                        $strMsg[$rowCount] .= "  Try to maintain an IDEAL level.";

                                    }

                                    echo $strMsg[$rowCount];


                                    ?>

                                </p>
                                <button class="btn btn-primary" onclick="window.print();" role="button">Print</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $rowCount++;
            }
        }
    }

}