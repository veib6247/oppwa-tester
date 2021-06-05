<?php

  echo '<div class="navbar-brand">
  <a class="navbar-item" href="#nav">
    <span class="icon is-medium">
    <i class="fas fa-wave-square"></i>
    </span>
    <span>
      <strong>
        OPPWA API Tester
      </strong>
    </span>
  </a>

  <!-- burger minimizes on mobile -->
  <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="nav_menu"
    :class="{\'is-active\': is_nav_burger_visible}" @click="toggle_burger">
    <span aria-hidden="true"></span>
    <span aria-hidden="true"></span>
    <span aria-hidden="true"></span>
  </a>
</div>'


?>