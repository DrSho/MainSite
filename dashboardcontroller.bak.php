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

        $dob = $this->getDOB($db_handle);

        if ((mysqli_num_rows($result) == 0)) {

            $this->norecords();
        } else {

            echo "<p>Click a row for a detail view.</p>";


            while ($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            $count = 1;
            foreach ($rows as $row) {
                $collapse = "collapse" . $count;
                ?>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title ">
                            <?= $count ?>.
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#<?= $collapse ?>"><?= $row['date'] ?></a>

                            <div style="float:right;">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-default btn-sm active"
                                   role="button">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-default btn-sm active"
                                   role="button">Delete</a>
                            </div>


                        </div>
                    </div>

                    <div id="<?= $collapse ?>" class="panel-collapse collapse ">

                        <?php


                        $diff = ($row['date'] - date('Y', strtotime($dob)));

                        ?>

                        Age:
                        <button class="data-default" disabled="disabled"><?= $diff ?></button>


                        Weight:
                        <button class="data-default" disabled="disabled"><?= $row['weight'] ?></button>

                        <ul class="list-group">


                            <li class="list-group-item"><?= $row['health_type1'] ?> |
                                <?= $row['health_type1_level'] ?> |

                                <?php
                                if ($row['health_type1_level'] >= 0 && $row['health_type1_level'] <= 149)
                                    echo "Normal";
                                else if ($row['health_type1_level'] >= 150 && $row['health_type1_level'] <= 199)
                                    echo "Borderline-high";
                                else if ($row['health_type1_level'] >= 200 && $row['health_type1_level'] <= 499)
                                    echo "High";
                                else
                                    echo "Very High";
                                ?>

                            </li>
                            <li class="list-group-item">
                                <?= $row['health_type2'] ?> |
                                <?= $row['health_type2_level'] ?> |

                                <?php
                                if ($row['health_type2_level'] >= 50 && $row['health_type2_level'] <= 99)
                                    echo "Ideal";
                                else if ($row['health_type2_level'] >= 100 && $row['health_type2_level'] <= 129)
                                    echo "Close to Ideal";
                                else if ($row['health_type2_level'] >= 130 && $row['health_type2_level'] <= 159)
                                    echo "Borderline-high";
                                else if ($row['health_type2_level'] >= 160 && $row['health_type2_level'] <= 189)
                                    echo "High";
                                else if ($row['health_type2_level'] >= 190 && $row['health_type2_level'] <= 300)
                                    echo "Very High";
                                ?>

                            </li>
                            <li class="list-group-item">
                                <?= $row['health_type3'] ?> |
                                <?= $row['health_type3_level'] ?> |

                                <?php
                                if ($row['health_type3_level'] >= 20 && $row['health_type3_level'] <= 39)
                                    echo "Low (high heart disease risk)";
                                else if ($row['health_type3_level'] >= 40 && $row['health_type3_level'] <= 59)
                                    echo "Normal (but the higher the better)";
                                else if ($row['health_type3_level'] >= 60 && $row['health_type3_level'] <= 90)
                                    echo "Best (offers protection against heart disease)";
                                ?>

                            </li>
                            <li class="list-group-item">
                                <?= $row['health_type4'] ?> |
                                <?= $row['health_type4_level'] ?> |

                                <?php
                                if ($row['health_type4_level'] >= 80 && $row['health_type4_level'] <= 200)
                                    echo "Ideal";
                                else if ($row['health_type4_level'] >= 201 && $row['health_type4_level'] <= 239)
                                    echo "Borderline-high";
                                else if ($row['health_type4_level'] >= 240 && $row['health_type4_level'] <= 500)
                                    echo "High";
                                ?>

                            </li>

                        </ul>
                        <div class="panel-footer"><p><span style="color:red;">Recommendation</span>: Information
                                goes here...</p></div>
                    </div>
                </div>

                <?php
                $count++;
            }

            $result->close();
            //$result->free();
        }
    }

}