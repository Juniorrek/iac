<?php
    require 'db_credentials.php';
    $conn = mysqli_connect($servername, $username, $password);
    /*$sql = "DROP DATABASE $dbname";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }*/
    $sql = "CREATE DATABASE $dbname";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }
    $sql = "USE $dbname";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }



    $sql = "CREATE TABLE acesso (
        data date,
        horario varchar(5),
        TAG bigint
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "CREATE TABLE cargo (
        idCargo serial,
        nome varchar(50),
        constraint pkcargo primary key(idCargo)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }


    $sql = "CREATE TABLE departamento (
        idDep serial,
        nome varchar(50),
        constraint pkdep primary key(idDep)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "CREATE TABLE filial (
        idFilial serial,
        nome varchar(50),
        constraint pkfilial primary key(idFilial)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "CREATE TABLE endereco (
        numero integer,
        CEP varchar(10),
        idEndereco serial,
        cidade varchar(100),
        bairro varchar(100),
        estado varchar(100),
        rua varchar(100),
        constraint pkEndereco primary key(idEndereco)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "CREATE TABLE TAG (
        tag bigint,
        CPF varchar(14),
        horaMin varchar(5),
        horaMax varchar(5),
        dias varchar(7),
        master boolean,
        constraint pktag primary key(tag)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "CREATE TABLE Usuario (
        CPF varchar(14),
        nome varchar(100),
        nasc date,
        foto varchar(100),
        adm boolean,
        cargo integer,
        filial integer,
        departamento integer,
        Telefone varchar(14),
        email varchar(100),
        Endereco integer,
        ativada boolean,
        Senha varchar(100),
        constraint pkusuario primary key(CPF)
    );";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "ALTER TABLE Usuario
        add constraint fkdep
            foreign key (departamento)
                references Departamento(idDep),
        add constraint fkcargo
            foreign key (cargo)
                references Cargo(idCargo),
        add constraint fkEndereco
            foreign key (Endereco)
                references Endereco(idEndereco),
        add constraint fkfilial
            foreign key (filial)
                references Filial(idFilial);";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "ALTER TABLE TAG
        add constraint fkcpfta
            foreign key (CPF)
                references Usuario(cpf);";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    $sql = "ALTER TABLE Acesso
        add constraint fktag
            foreign key (TAG)
                references TAG(tag);";
    if (!mysqli_query($conn, $sql)) {
      die("Error acess database: " . mysqli_error($conn));
  }

    mysqli_close($conn);
 ?>
