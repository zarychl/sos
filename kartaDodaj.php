<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_POST['dataZlecenia']))
{
    if(isset($_POST['staff2']))
        $cId = dodajKarte($_POST['dataZlecenia'], $_POST['przychodnia'], $_POST['lekarz'], $_POST['cars'], $_POST['staff1'], $_POST['staff2']);
    else
        $cId = dodajKarte($_POST['dataZlecenia'], $_POST['przychodnia'], $_POST['lekarz'], $_POST['cars'], $_POST['staff1'], -1);

    header("Location: przejazdy.php?idkarty=". $cId);
}
require_once("includes/navbar.php");
require_once("includes/sidebar.php");


?>
    <div id="content-wrapper">

        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Zlecenia</li>
                <li class="breadcrumb-item active">Dodaj nowe</li>
            </ol>

            <!-- Area Chart Example-->
            <div class="card mb-3">
                <div class="card-header">
                    Karta
                </div>
                <div class="card-body">

                    <form method="post">
                    <div class="row">
                        <div class=" col-sm-6">
                            <div class="form-group">
                                <label >Data zlecenia</label>
                                <input type="date" class="form-control" name="dataZlecenia" value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                            <div class="form-group">
                                <label >Samochód</label>
                                <select class="form-control" name="cars">
                                    <?php
                $cars = getAllCars();
                foreach ($cars as $key => $value)
                {
                    if($cars[$key]['czyWBazie'] == 0)
                        continue;

                    echo "<option $option value='". $cars[$key]['id'] ."'>". $cars[$key]['nazwa']. " [".$cars[$key]['tablica']. "]</option>";
                }
                ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label >Załoga</label>
                                <select class="form-control" name="staff1">
                                    <?php
                $staff = getAllStaff();
                foreach ($staff as $key => $value)
                {
                    echo "<option value='". $cars[$key]['id'] ."'>". $staff[$key]['imie']. " ".$staff[$key]['nazwisko']. "</option>";
                }
                ?>
                                </select>
                                <select class="form-control" name="staff2">
                                    <option disabled selected value>--- WYBIERZ ---</option>
                                    <?php
                $staff = getAllStaff();
                foreach ($staff as $key => $value)
                {
                    echo "<option value='". $staff[$key]['id'] ."'>". $staff[$key]['imie']. " ".$staff[$key]['nazwisko']. "</option>";
                }
                ?>
                                </select>
                            </div>


                        </div>

                        <div class="d-inline col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Dysponent</label>
                                    <input type="text" class="form-control" name="przychodnia" placeholder="Przychodnia" required>
                                    <input type="text" class="form-control" name="lekarz" placeholder="Lekarz" required>
                                </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </form>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright ©</span>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.content-wrapper -->
    <?php
require_once("includes/footer.php");
?>