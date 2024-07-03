<?php include "header.php"; 
include "../database/db.php";
?>
<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Customer/</span> Shop</h4>

<!-- Basic Layout -->
<div class='row'>
<div class='col-xl'>
      <div class='card mb-4'>
      <table class="table">
        <tbody><tr>
            <td>Min: <input type="text" id="min" name="min"> Max: <input type="text" id="max" name="max"></td>
        </tr>

    </tbody></table>
      </div>
    </div>
</div>
<div class="row">
    
    <div class="col-xl">
      <div class="card mb-4">
        <div class="card-header p-0">
          <div class="nav-align-top">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button type="button" class="nav-link active waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                  Catering
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">
                  Decoration
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-messages" aria-controls="navs-top-messages" aria-selected="false" tabindex="-1">
                  DJ
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-venue" aria-controls="navs-top-venue" aria-selected="false" tabindex="-1">
                  Venue
                </button>
              </li>
            <span class="tab-slider" style="left: 0px; width: 91.1719px; bottom: 0px;"></span></ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            
            <!--TAB 1-->
            <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
              <div class="table-responsive text-nowrap">
                
                <?php
                    $qry = "
                    SELECT * FROM tbl_vendor_data WHERE category='catering' AND status=1;
                    ";
                    $result = mysqli_query($conn,$qry);
                
                ?>
                <table id="catable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                  
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th></th>
                    </thead>
                    <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['price']."</td>";
                        echo "<td>".$row['details']."</td>";
                        echo "<td>";
                        echo "<a href='cart.php?id=".$row['id']."' class='btn badge rounded-pill bg-label-success me-1'>Add to cart</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
                </table>
              </div>
            
            </div>
            <!--END TAB 1-->

            <!--TAB 2-->
            <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
              <div class="table-responsive text-nowrap">
                <?php
                    $qry = "
                    SELECT * FROM tbl_vendor_data WHERE category='decoration' AND status=1;
                    ";
                    $result = mysqli_query($conn,$qry);
                
                ?>
                <table id="detable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                  
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th></th>
                    </thead>
                    <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['price']."</td>";
                        echo "<td>".$row['details']."</td>";
                        echo "<td>";
                        echo "<a href='cart.php?id=".$row['id']."' class='btn badge rounded-pill bg-label-success me-1'>Add to cart</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
                </table>
              </div>
            


            </div>
            <!--END TAB 2-->

            <!--TAB 3-->
            <div class="tab-pane fade" id="navs-top-messages" role="tabpanel">
              <div class="table-responsive text-nowrap">
                <?php
                    $qry = "
                    SELECT * FROM tbl_vendor_data WHERE category='dj' AND status=1;
                    ";
                    $result = mysqli_query($conn,$qry);
                
                ?>
                <table id="djtable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                  
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th></th>
                    </thead>
                    <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['price']."</td>";
                        echo "<td>".$row['details']."</td>";
                        echo "<td>";
                        echo "<a href='cart.php?id=".$row['id']."' class='btn badge rounded-pill bg-label-success me-1'>Add to cart</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
                </table>
              </div>
            
            </div>

            <!--TAB 4-->
            <div class="tab-pane fade" id="navs-top-venue" role="tabpanel">
              <div class="table-responsive text-nowrap">
                <?php
                    $qry = "
                    SELECT * FROM tbl_venue WHERE status=1;
                    ";
                    $result = mysqli_query($conn,$qry);
                
                ?>
                <table id="vetable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Address</th>
                        <th></th>
                    </thead>
                    <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        echo "<td><img src='".$row['image_path']."' width='40' height='40'></td>";
                        echo "<td>".$row['price']."</td>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['capacity']."</td>";
                        echo "<td>".$row['address']."</td>";
                        echo "<td>";
                        echo "<a href='cart.php?vid=".$row['id']."' class='btn badge rounded-pill bg-label-success me-1'>Add to cart</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
                </table>
              </div>
            
            </div>

          </div>
        </div>
      </div>
    </div>



<?php include "footer.php"; ?>
<script src="assets/DataTables/datatables.min.js"></script>
<script>
    var ca = new DataTable("#catable");
    var de = new DataTable("#detable");
    var dj = new DataTable("#djtable");
    var ve = new DataTable("#vetable");
    const minEl = document.querySelector('#min');
    const maxEl = document.querySelector('#max');
    
    // Custom range filtering function
    DataTable.ext.search.push(function (settings, data, dataIndex) {
        let min = parseInt(minEl.value, 10);
        let max = parseInt(maxEl.value, 10);
        let price = parseFloat(data[1]) || 0; // use data for the age column
    
        if (
            (isNaN(min) && isNaN(max)) ||
            (isNaN(min) && price <= max) ||
            (min <= price && isNaN(max)) ||
            (min <= price && price <= max)
        ) {
            return true;
        }
    
        return false;
    });

    // Changes to the inputs will trigger a redraw to update the table
    minEl.addEventListener('input', function () {
        ca.draw();
        de.draw();
        dj.draw();
        ve.draw();
    });
    maxEl.addEventListener('input', function () {
      ca.draw();
        de.draw();
        dj.draw();
        ve.draw();
    });
</script>