<?php

/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/25/2017
 * Time: 10:15 PM
 */
class AdminController
{
    private $adminId;
    private $adminEmail;
    private $adminPass;
    private $adminFirstName;
    private $adminMiddleName;
    private $adminLastName;
    private $adminTitle;
    private $adminGender;

    public function loadData($db_handle)
    {
        //Get all user info
        $query = "SELECT * FROM admin WHERE id = '" . $_SESSION['userId'] . "'";

        $result = $db_handle->runQuery($query);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        $this->adminId = ($row['user_account_id']);
        $this->adminEmail = ($row['email']);
        $this->adminPass = ($row['password']);
        $this->adminGender = ($row['gender']);
        $this->adminFirstName = ($row['first_name']);
        $this->adminMiddleName = ($row['middle_name']);
        $this->adminLastName = ($row['last_name']);
        $this->adminTitle = ($row['address']);

    }


    public function showrecords($result)
    {


        if ((mysqli_num_rows($result) == 0)) {

            $this->norecords();
        } else {

            while ($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            ?>

            <table id="myTable" class="table table-striped">
            <thead>
            <tr class="header">

                <th>#</th>
                <th>Patient ID#</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Action</th>
            </tr>
</thead>
<tbody>
            <?php
            $counter = 1;
            foreach ($rows as $row) { ?>
            <tr>
                <td><?= $counter ?></td>
                <td><?=$row['user_account_id']?></td>
                <td><?= ucwords($row['first_name']) ?></td>
                <td><?= ucwords($row['middle_name']) ?></td>
                <td><?= ucwords($row['last_name']) ?></td>
                <td><a href="details.php?id=<?= $row['user_account_id'] ?>&pid=" class="btn btn-primary" role="button">View</a></td>
            </tr>

            <?php
            $counter++;
        }
        echo "</tbody></table>";
    }
}



/**
 * @return mixed
 */
public
function getAdminId()
{
    return $this->adminId;
}

/**
 * @param mixed $adminId
 */
public
function setAdminId($adminId)
{
    $this->adminId = $adminId;
}

/**
 * @return mixed
 */
public
function getAdminUser()
{
    return $this->adminUser;
}

/**
 * @param mixed $adminUser
 */
public
function setAdminUser($adminUser)
{
    $this->adminUser = $adminUser;
}

/**
 * @return mixed
 */
public
function getAdminPass()
{
    return $this->adminPass;
}

/**
 * @param mixed $adminPass
 */
public
function setAdminPass($adminPass)
{
    $this->adminPass = $adminPass;
}

/**
 * @return mixed
 */
public
function getAdminFirstName()
{
    return $this->adminFirstName;
}

/**
 * @param mixed $adminFirstName
 */
public
function setAdminFirstName($adminFirstName)
{
    $this->adminFirstName = $adminFirstName;
}

/**
 * @return mixed
 */
public
function getAdminMiddleName()
{
    return $this->adminMiddleName;
}

/**
 * @param mixed $adminMiddleName
 */
public
function setAdminMiddleName($adminMiddleName)
{
    $this->adminMiddleName = $adminMiddleName;
}

/**
 * @return mixed
 */
public
function getAdminLastName()
{
    return $this->adminLastName;
}

/**
 * @param mixed $adminLastName
 */
public
function setAdminLastName($adminLastName)
{
    $this->adminLastName = $adminLastName;
}

/**
 * @return mixed
 */
public
function getAdminTitle()
{
    return $this->adminTitle;
}

/**
 * @param mixed $adminTitle
 */
public
function setAdminTitle($adminTitle)
{
    $this->adminTitle = $adminTitle;
}

/**
 * @return mixed
 */
public
function getAdminGender()
{
    return $this->adminGender;
}

/**
 * @param mixed $adminGender
 */
public
function setAdminGender($adminGender)
{
    $this->adminGender = $adminGender;
}


}