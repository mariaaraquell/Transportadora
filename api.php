<?php
//CONFIGURAÇÕES INICIAIS
//Define a resposta da API, neste caso em JSON
header("Content-Type: application/json; charset=utf-8");
//Permite o acesso à API de todas as origens
header("Access-Control-Allow-Origin: *");
//Permite os métodos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

//necessário para utilizarmos o PUT
$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode(["mensagem" => "Back-end da transportadora respondendo!"]);
    exit;
}

// CRUD - Cliente
function createCliente($con, $dados){
$stmt = $con->prepare(
"INSERT INTO CLIENTE (NOME, ENDERECO, TELEFONE, EMAIL)
VALUES (?,?,?,?)"
);
$stmt->execute([$dados["nome"],
$dados["endereco"],
$dados["telefone"],
$dados["email"]]);
http_response_code(201);
echo json_encode(
["mensagem" => "Cliente criado com sucesso",
"id" => $con->lastInsertId()]
);
}

//READ
function readCliente($con){
$stmt = $con->query("SELECT * FROM Cliente");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

//UPDATE
function updateCliente($con, $id, $dados){
$stmt = $con->prepare(
"UPDATE Cliente SET nome = ?, endereco = ?, telefone = ?, email = ?
WHERE idCliente = ?"
);
$stmt->execute([$dados["nome"],
$dados["endereco"],
$dados["telefone"],
$dados["email"],
$id]);
echo json_encode(["mensagem" => "Cliente atualizado com sucesso!"]);
}

//DELETE
function deleteCliente($con, $id){
$stmt = $con->prepare("DELETE FROM Cliente WHERE idCliente = ?");
$stmt->execute([$id]);
echo json_encode(["mensagem" => "Cliente removido com sucesso!"]);
}

// CRUD - Funcionario
function createFuncionario($con, $dados){
$stmt = $con->prepare(
"INSERT INTO FUNCIONARIO (NOME, CARGO, SALARIO)
VALUES (?,?,?)"
);
$stmt->execute([$dados["nome"],
$dados["cargo"],
$dados["salario"]]);
http_response_code(201);
echo json_encode(
["mensagem" => "Funcionario criado com sucesso",
"id" => $con->lastInsertId()]
);
}

//READ
function readFuncionario($con){
$stmt = $con->query("SELECT * FROM funcionario");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

//UPDATE
function updateFuncionario($con, $id, $dados){
$stmt = $con->prepare(
"UPDATE Funcionario SET nome = ?, cargo = ?, salario = ?
WHERE idFuncionario = ?"
);
$stmt->execute([$dados["nome"],
$dados["cargo"],
$dados["salario"],
$id]);
echo json_encode(["mensagem" => "Funcionario atualizado com sucesso!"]);
}

//DELETE
function deleteFuncionario($con, $id){
$stmt = $con->prepare("DELETE FROM Funcionario WHERE idFuncionario = ?");
$stmt->execute([$id]);
echo json_encode(["mensagem" => "Funcionario removido com sucesso!"]);
}

// CRUD - Veiculo
function createVeiculo($con, $dados){
$stmt = $con->prepare(
"INSERT INTO Veiculo (placa, modelo, capacidade)
VALUES (?,?,?)"
);
$stmt->execute([$dados["placa"],
$dados["modelo"],
$dados["capacidade"]]);
http_response_code(201);
echo json_encode(
["mensagem" => "Veiculo criado com sucesso",
"placa" => $dados["placa"]]
);
}

//READ
function readVeiculo($con){
$stmt = $con->query("SELECT * FROM Veiculo");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

//UPDATE
function updateVeiculo($con, $id, $dados){
$stmt = $con->prepare(
"UPDATE Veiculo SET modelo = ?, capacidade = ?
WHERE placa = ?"
);
$stmt->execute([$dados["modelo"],
$dados["capacidade"],
$id]);
echo json_encode(["mensagem" => "Veiculo atualizado com sucesso!"]);
}

//DELETE
function deleteVeiculo($con, $id){
$stmt = $con->prepare("DELETE FROM Veiculo WHERE placa = ?");
$stmt->execute([$id]);
echo json_encode(["mensagem" => "Veiculo removido com sucesso!"]);
}

//ROTAS
if ($rota == "cliente"){
switch($metodo){
case "GET":
readCliente($con);
break;
case "POST":
createCliente($con, $corpo);
break;
case "PUT":
updateCliente($con, $_GET["id"], $corpo);
break;
case "DELETE":
deleteCliente($con, $_GET["id"]);
break;
default:
teste();
}
}else if($rota === "funcionario"){
switch($metodo){
case "GET":
readFuncionario($con);
break;
case "POST":
createFuncionario($con, $corpo);
break;
case "PUT":
updateFuncionario($con, $_GET["id"], $corpo);
break;
case "DELETE":
deleteFuncionario($con, $_GET["id"]);
break;
default:
teste();
}
}else if($rota === "veiculo"){
switch($metodo){
case "GET":
readVeiculo($con);
break;
case "POST":
createVeiculo($con, $corpo);
break;
case "PUT":
updateVeiculo($con, $_GET["id"], $corpo);
break;
case "DELETE":
deleteVeiculo($con, $_GET["id"]);
break;
default:
teste();
}
}