<?php

class Testes{

  public $id;
  public $email_usuario;
  public $data_teste;
  public $antesCafe;
  public $depoisCafe;
  public $antesAlmoco;
  public $depoisAlmoco;
  public $antesJantar;
  public $depoisJantar;
  public $madrugada;
}

interface TesteDAOInterface{
  public function buildTestes($data); //função para guardar dados do banco e vice-versa
  public function findAll($email_usuario); //função para mostrar testes do dia na table
  public function findAllTests($email_usuario); //função para mostrar testes de 20 dias atrás
  public function verifyNullTestes($email_usuario); //função para verificar se há uma coluna no BD em null
  public function getLatestTest($email_usuario);// Função para verificar o ultimo teste lançado
  public function getWeeklyAverage($email_usuario);// Função para calcular a média da semana em testes HGT
  public function getMesTest($email_usuario); //calcular a média do mês
  public function countTodayTest($email_usuario);// Função para verificar a quantidade de testes diarios
  public function findByTeste($data_teste); //função para verificar no BD os testes registrados
  public function findByEmailAndDate($email_usuario, $data_teste); //função para verificar se o usuario_data existe
  public function create(Testes $testes); //função para inserir dados
  public function update(Testes $testes); //função para atualizar os dados no BD
  public function destroy($email_usuario);
}