
<script>
  //variavel BD ultimo teste
  const ultimoTeste = <?= $valorExibir ?>;

  //variavel BD testes realizados
  const dadosTeste = <?php echo json_encode($registroSeguro); ?>;

  //variavel BD média semana
  const mediaSemanaV = <?= $mediaSemana ?>;

  //variavel BD média mês
  const mediaMesV = <?= $mediaMes ?>;
</script>
<script src="JS/main.js"></script>
</body>
</html>