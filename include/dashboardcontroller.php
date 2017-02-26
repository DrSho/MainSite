<?php

/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/23/2017
 * Time: 9:54 AM
 */

class DashboardController
{

    public function loadBenchmark($db_handle, $account){
        //$benchmark = new BenchmarkModel();

        //Get type ID
        $queryTypeId = "SELECT DISTINCT health_type_id, health_type FROM health_benchmark";
        $resultTypeId = $db_handle->runQuery($queryTypeId);

        while ($rowTypeId = $resultTypeId->fetch_array(MYSQLI_ASSOC)){

            $queryData = "SELECT * FROM health_benchmark WHERE health_type_id = '".$rowTypeId['health_type_id']."'";
            $resultData = $db_handle->runQuery($queryData);

            while ($rowData = $resultData->fetch_array(MYSQLI_ASSOC)) {

                $hData = ["condition"=>$rowData['display_result'], "min"=>$rowData['lowest_value'], "max"=>$rowData['highest_value']];

            }


            $hbArray[] = ["id"=>$rowTypeId['health_type_id'],$rowTypeId['health_type']=>$hData];
        }

        //Record benchmark data to array instance
        $account->hbArray = ($hbArray);

    }


    public function loadData($db_handle, $account)
    {

        //$account = new AccountController();

        //Get all user info
        $query = "SELECT * FROM user_account
                    JOIN user_password
                    ON user_account.user_account_id = user_password.user_account_id
                    JOIN user_address
                    ON user_account.user_account_id = user_address.user_account_id
                    WHERE user_account.user_account_id = '".$_SESSION['userId']."'";

        $result = $db_handle->runQuery($query);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        $account->user_account_id=($row['user_account_id']);
        $account->email=($row['email']);
        $account->password=($row['password']);
        $account->gender=($row['gender']);
        $account->first_name=($row['first_name']);
        $account->middle_name=($row['middle_name']);
        $account->last_name=($row['last_name']);
        $account->address=($row['address']);
        $account->address2=($row['address2']);
        $account->city=($row['city']);
        $account->state=($row['state']);
        $account->zip=($row['zip']);
        $account->month=($row['city']);
        $account->day=($row['city']);
        $account->year=($row['city']);
        $account->feet=($row['feet']);
        $account->inches=($row['inches']);
    }


    public function norecords()
    {
        echo "<h1>No records found.</h1>";

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

    public function showrecords($db_handle, $account, $result)
    {

//        $benchmark = $account->getHbArray();
        $dob = $this->getDOB($db_handle);
        $highTypes = "";

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
                                        <th>MY LEVEL
                                            <small>(mmol/L)</small>
                                        </th>
                                        <th>IDEAL LEVEL
                                            <small>(mmol/L)</small>
                                        </th>
                                        <th>MY CONDITION</th>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type2']) ?></td>
                                        <td><?= $row['health_type2_level'] ?></td>
                                        <td><?=LDL_MIN ?> - <?=LDL_MAX?></td>

                                        <?php
                                        //===========================
                                        //========== LDL
                                        //===========================
                                        if ($row['health_type2_level'] >= LDL_MIN && $row['health_type2_level'] <= LDL1)
                                            echo "<td class='success'>" . strtoupper(LDL1_TEXT);
                                        else if ($row['health_type2_level'] > LDL1 && $row['health_type2_level'] <= LDL2)
                                            echo "<td>" . strtoupper(LDL2_TEXT);
                                        else if ($row['health_type2_level'] > LDL2 && $row['health_type2_level'] <= LDL3)
                                            echo "<td class='info'>" . strtoupper(LDL3_TEXT);
                                        else if ($row['health_type2_level'] > LDL3 && $row['health_type2_level'] <= LDL4)
                                            echo "<td class='warning'>" . strtoupper(LDL4_TEXT);
                                        else if ($row['health_type2_level'] > LDL4 && $row['health_type2_level'] <= LDL5) {
                                            echo "<td class='danger'>" . strtoupper(LDL5_TEXT);
                                            $highTypes[$rowCount][$colCount] = ($row['health_type2']);
                                            $colCount++;
                                        }
                                        echo "</td>";
                                        ?>

                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type3']) ?></td>
                                        <td><?= $row['health_type3_level'] ?></td>
                                        <td><?=HDL_MIN ?> - <?=HDL_MAX?></td>
                                        <?php

                                        //===========================
                                        //========== HDL
                                        //===========================

                                        if ($row['health_type3_level'] >= HDL_MIN && $row['health_type3_level'] <= HDL1) {
                                            $highTypes[$rowCount][$colCount] = ($row['health_type3']);
                                            $colCount++;
                                            echo "<td class='danger'>" . strtoupper(HDL1_TEXT);

                                        } else if ($row['health_type3_level'] > HDL1 && $row['health_type3_level'] <= HDL2)
                                            echo "<td class='warning'>" . strtoupper(HDL2_TEXT);
                                        else if ($row['health_type3_level'] > HDL2 && $row['health_type3_level'] <= HDL3)
                                            echo "<td class='success'>" . strtoupper(HDL3_TEXT);
                                        echo "</td>";
                                        ?>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type1']) ?></td>
                                        <td><?= $row['health_type1_level'] ?></td>
                                        <td><?=TGL_MIN ?> - <?=TGL_MAX?></td>
                                        <?php

                                        //===========================
                                        //========== TRIGLYCERIDES
                                        //===========================

                                        if ($row['health_type1_level'] >= TGL_MIN && $row['health_type1_level'] <= TGL1)
                                            echo "<td class='success'>" . strtoupper(TGL1_TEXT);
                                        else if ($row['health_type1_level'] > TGL1 && $row['health_type1_level'] <= TGL2)
                                            echo "<td class='info'>" . strtoupper(TGL2_TEXT);
                                        else if ($row['health_type1_level'] > TGL2 && $row['health_type1_level'] <= TGL3)
                                            echo "<td class='warning'>" . strtoupper(TGL3_TEXT);
                                        else {
                                            echo "<td class='danger'>" . strtoupper(TGL4_TEXT);
                                            $highTypes[$rowCount][$colCount] = ($row['health_type1']);
                                            $colCount++;
                                        }
                                        echo "</td>";
                                        ?>
                                    </tr>
                                    <tr>
                                        <td><?= strtoupper($row['health_type4']) ?></td>
                                        <td><?= $row['health_type4_level'] ?></td>
                                        <td><?=CHL_MIN ?> - <?=CHL_MAX?></td>
                                        <?php

                                        //===========================
                                        //========== CHOLESTEROL
                                        //===========================

                                        if ($row['health_type4_level'] >= CHL_MIN && $row['health_type4_level'] <= CHL1)
                                            echo "<td class='success'>" . strtoupper(CHL1_TEXT);
                                        else if ($row['health_type4_level'] > CHL1 && $row['health_type4_level'] <= CHL2)
                                            echo "<td class='warning'>" . strtoupper(CHL2_TEXT);
                                        else if ($row['health_type4_level'] > CHL2 && $row['health_type4_level'] <= CHL3) {
                                            echo "<td class='danger'>" . strtoupper(CHL3_TEXT);
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
                                        $strMsg[$rowCount] = NORMAL_HEALTH;
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