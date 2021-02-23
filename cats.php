<?php
  $breeds = [];
  $fact = '';
  if(isset($_GET['breed'])) {
    $limit = 10;
    $page = rand(1, 10);
    $response = file_get_contents("https://catfact.ninja/breeds?page={$page}&limit={$limit}");
    $response = json_decode($response);
    $breeds = $response->data;
    if(count($breeds) == 0) {
      $page = rand(1, $response->last_page);
      $response = file_get_contents("https://catfact.ninja/breeds?page={$page}&limit={$limit}");
      $response = json_decode($response);
      $breeds = $response->data;
    }
  }

  if(isset($_GET['facts'])) {
    $response = file_get_contents('https://catfact.ninja/fact');
    $response = json_decode($response);
    $fact = $response->fact;
    if($response->length == 0) {
      $fact = "Ooops! Something seems to be wrong with this right now";
    }
  }
  include 'header.php';
?>

    <div class="centerBlock">
      <h2>Cats</h2>
      <p>
        <form method="GET" action="cats.php">
          <button name="breed" value="breed">Breed</button>
        </from>
        <form method="GET" action="cats.php">
          <button name="facts" value="facts">Fact</button>
        </from>
      </p>
    </div>
    <?php if(count($breeds) > 0 || $fact != '') {
      echo "<div class='centerBlock'>";
        if(count($breeds) > 0) {
          echo "<table>
                <thead>
                  <th>Breed</th>
                  <th>Country</th>
                  <th>Origin</th>
                  <th>Coat</th>
                  <th>Pattern</th>
                </thead>
                <tbody>";
          foreach($breeds as $breed) {
            echo "
                <tr>
                  <td>{$breed->breed}</td>
                  <td>{$breed->country}</td>
                  <td>{$breed->origin}</td>
                  <td>{$breed->coat}</td>
                  <td>{$breed->pattern}</td>
                </tr>
            ";
          }
          echo "</tbody> </table>";
        }
        if($fact) {
            echo "
              <table>
                <tr>
                  <th>Fact</th><td>{$fact}</td>
                </tr>
              </table>
            ";
        }
      echo "</div>";
    }
   include 'footer.php';?>
  </body>
</html>