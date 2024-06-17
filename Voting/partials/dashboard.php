<?php
session_start();
if (!isset($_SESSION['id'])) {
   header('location:../');
}
$data = $_SESSION['data'];
// Use the null coalescing operator to assign default values if the keys are not set
$status = $data['status'] ?? 0;
$mobile = $data['mobile'] ?? '';
// Use a ternary operator to assign a class name based on the status
$status_class = $status == 1 ? 'text-success' : 'text-success';
$status_text = $status == 1 ? 'Voted' : 'Pending';

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device=width,
    initial-scale=1.0">
   <title>Voting system -Dashboard</title>

   <!-- Bootstrap css link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

   <!-- css file -->
   <link rel="stylesheet" href="../style.css">
</head>

<body>
   <div class="container my-5">
      <a href="../"><button class="btn btn-dark text-light px-3">Back</button></a>
      <a href="logout.php"><button class="btn btn-dark text-light px-3">Logout</button></a>
      <h1 class="my-3">Voting system</h1>
      <div class="row my-5">
         <div class="col-md-7">
            <?php
            if (isset($_SESSION['group'])) {
               $group = $_SESSION['group'];
               for ($i = 0; $i < count($group); $i++) {
            ?>
                  <div class="row">
                     <div class="col-md-4">
                        <img src="../uploads/<?php echo $group[$i]['photo'] ?>" alt="Group image">
                     </div>
                     <div class="col-md-8">
                        <strong class="text-dark h5">Group name:</strong>
                        <?php echo $group[$i]['username'] ?><br>
                        <strong class="text-dark h5">Votes:</strong>
                        <?php echo $group[$i]['votes'] ?><br>
                     </div>
                  </div>

                  <form action="../actions/voting.php" method="post">
                     <input type="hidden" name="groupvotes" value="<?php echo $group[$i]['votes']
                                                                     ?>">
                     <input type="hidden" name="groupid" value="<?php echo $group[$i]['id'] ?>">

                     <?php
                     if ($_SESSION['status'] == 1) {
                     ?>
                        <button class="bg-success disable my-3 text-white px-3">Voted</button>
                     <?php
                     } else {
                     ?>
                        <button class="bg-danger my-3 text-white px-3" type="submit">Vote</button>
                     <?php
                     }

                     ?>

                  </form>
                  <hr>
               <?php
               }
            } else {
               ?>
               <div class="container">
                  <p>No groups to display</p>
               </div>
            <?php
            }
            ?>
            <!-- groups -->
            <div class="row">
               <div class="col-md-4">

               </div>
               <div class="col-md-8">

               </div>
            </div>
         </div>
         <div class="col-md-5">
            <!-- user profile -->
            <img src="../uploads/<?php echo $data['photo'] ?>" alt="User image">
            <br>
            <br>
            <strong class="text-dark h5">Name:</strong>
            <?php echo $data['username']; ?><br><br>
            <strong class="text-dark h5">Mobile:</strong><?php echo $data['mobile']; ?>
            <br><br>
            <strong class="text-dark h5">Status:</strong><span class="<?php echo $status_class; ?>">
               <?php echo $status_text; ?></span>
            <br><br>
         </div>
      </div>

   </div>
</body>

</html>