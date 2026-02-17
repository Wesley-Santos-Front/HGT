<?php
require_once ("templates/header.php");
require_once ("DAO/usuarioDAO.php");
require_once ("DAO/testesDAO.php");
require_once ("models/testes.php");

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$usuarioData = $usuarioDao->verifyToken(true);
$testeDao = new TestesDAO($conn, $BASE_URL);

//verificar ultimo teste
$ultimoTeste = $testeDao->getLatestTest($usuarioData->email);

if($ultimoTeste){

  $colunas1 = ['madrugada', 'antesCafe', 'depoisCafe', 'antesAlmoco', 'depoisAlmoco', 'antesJantar', 'depoisJantar'];

  $valorExibir = "0";

  foreach (array_reverse($colunas1) as $coluna){
    if(!empty($ultimoTeste[$coluna]) && $ultimoTeste[$coluna] > 0){
      $valorExibir = $ultimoTeste[$coluna];
      break;
    }
  }
}

//verificar quantidade de testes
$quantidadeTestes = $testeDao->countTodayTest($usuarioData->email);

//verifica a média da semana
$mediaSemana = $testeDao->getWeeklyAverage($usuarioData->email);

//verifica a média do mês
$mediaMes = $testeDao->getMesTest($usuarioData->email);

?>

<!--pagina home-->
<section class="container">

<!--Views-->
<div class="views">
  <div class="v1">
    <ion-icon name="water-outline"></ion-icon>
    <h4>Último teste</h4>
    <p id="ultimoTesteP"><span id="ultimoTeste"></span> mg/dl</p>
  </div>

   <div class="v1">
    <ion-icon name="today-outline"></ion-icon>
    <h4>Média da semana</h4>
    <p id="mediaSemanaP"><span id="mediaSemana"></span> mg/dl</p>
  </div>

   <div class="v1">
    <ion-icon name="stats-chart-outline"></ion-icon>
    <h4>Média do mês</h4>
    <p id="mediaMesP"><span id="mediaMes"></span> mg/dl</p>
  </div>

   <div class="v1">
    <ion-icon name="stopwatch-outline"></ion-icon>
    <h4>Quantidade de testes hoje</h4>
    <p><span><?= $quantidadeTestes ?></span></p>
  </div>

  <!--Grafico-->
</div>
<div class="grafico">
  <canvas id="myChart"></canvas>
  <div class="butTeste">
    <button id="butModal">+ Registrar novo teste</button>
  </div>
</div>

<!--Tabela de resultados HGT-->
<div class="tabela">
  <div class="title1">
  <h3>Testes Hoje</h3>
  <a href="<?= $BASE_URL ?>allTests">Ver todos</a>
  </div>
  <table>
    <thead>
      <tr>
        <th><?= $data?></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($testesLan as $testesL): ?>
        <?php 
            // Pegamos o valor daquela coluna no registro de hoje
            $valor = (!empty($test[$testesL])) ? $test[$testesL] : 0;
        ?>

        <?php if($valor > 0) : ?>
    <tr>
      <td><?= ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $testesL)) ?></td>
      <td><?= $valor ?> mg/dL</td>
      <td>
        <?php if($valor > 115) : ?>
          <span style="color: #e74c3c; font-weight:bold">Alto</span>
          <?php elseif($valor < 80) : ?>
            <span style="color: #F1C40F; font-weight:bold">Baixo</span>
            <?php else : ?>
              <span style="color: #2ecc71; font-weight:bold">Normal</span>
              <?php endif; ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>

    <?php if(empty($test) || array_sum(array_intersect_key($test, array_flip($testesLan))) == 0): ?>
        <tr>
          <td colspan="3" style="text-align:center;">Nenhum teste registrado hoje.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</section>

<!--ionicons-->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<!--Chart.js-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--footer-->
<?php
require_once ("templates/footer.php");
?>