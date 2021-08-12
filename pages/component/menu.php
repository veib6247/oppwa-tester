<?php

  echo '<div id="nav_menu" class="navbar-menu" :class="{\'is-active\': is_menu_visible}">
  <div class="navbar-start">
    <a class="navbar-item" href="copy-and-pay.php">
      <span class="icon is-medium">
        <i class="fas fa-credit-card"></i>
      </span>
      <span>
        CopyandPay
      </span>


    </a>

    <a class="navbar-item" href="server-to-server.php">
      <span class="icon is-medium">
        <i class="fas fa-server"></i>
      </span>
      <span>
        Server to Server
      </span>

    </a>

    <a class="navbar-item" href="query-transaction.php">
      <span class="icon is-medium">
        <i class="fas fa-search"></i>
      </span>
      <span>
        Query Transaction
      </span>

    </a>

    <a class="navbar-item" href="xml.php">
      <span class="icon is-medium">
        <i class="fas fa-file-code"></i>
      </span>
      <span>
        XML Transactions/Query
      </span>

    </a>

    <a class="navbar-item" href="settlements.php">
      <span class="icon is-medium">
        <i class="fas fa-file-invoice"></i>
      </span>
      <span>
        Settlements
      </span>

    </a>

    <a class="navbar-item" href="bin-checker.php">
      <span class="icon is-medium">
        <i class="fas fa-university"></i>
      </span>
      <span>
        BIN Checker
      </span>

    </a>

    <a class="navbar-item" href="batch-testing.php">
      <span class="icon is-medium">
        <i class="fas fa-stream"></i>
      </span>
      <span>
        Batch Testing <span class="has-text-warning">(Soon)</span>
      </span>

    </a>


  </div>

  <div class="navbar-end">
    <div class="navbar-item">
      Was this tool useful?
    </div>
    
    <div class="navbar-item">
      <a class="button is-rounded is-primary" href="https://paypal.me/bryanolandres?locale.x=en_US" target="_blank">
        <span class="icon is-medium">
          <i class="fab fa-paypal"></i>
        </span>
        <span>
          Consider Donating!
        </span>
      </a>
    </div>
  </div>

</div>'

?>