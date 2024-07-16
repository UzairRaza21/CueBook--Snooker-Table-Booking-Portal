<?php
$search_value = $_POST["search"];

include "conn.php";

$sql_search = "SELECT * FROM `{}` WHERE `customer_visit_date` LIKE '%{$search_value}%'";

$result = mysqli_query($conn, $sql_search) or die("SQL Query for load data Failed");
 
if(mysqli_num_rows($result) > 0){
    echo '<div id="ads-container">';

    while($row = mysqli_fetch_assoc($result)){
        
        echo '
                


        </div>';
    }

    echo '</div>';
    mysqli_close($conn);

} else {
    echo "<h2>No Record Found</h2>";
}
?>