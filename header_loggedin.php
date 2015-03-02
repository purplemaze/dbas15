<header>
   <h1><a href="index.php?<?php echo htmlspecialchars($_SESSION["_username"]); ?>"> crowdSales.com</a></h1>
  <nav>
    <ul>
      <li><a href="sell.php?<?php echo htmlspecialchars($_SESSION["_username"]); ?>">Sell Item</a></li>
      <li><a href="index.php?<?php echo htmlspecialchars($_SESSION["_username"]); ?>">For Sale</a></li>
      <li><a href="deliver.php?<?php echo htmlspecialchars($_SESSION["_username"]); ?>">Deliver Item</a></li>
      <li><a id="login" href="logout.php">Log out</a></li>
      <li><a href="account.php?<?php echo htmlspecialchars($_SESSION["_username"]); ?>">My Account</a></li>
    </ul>
  </nav>
</header>