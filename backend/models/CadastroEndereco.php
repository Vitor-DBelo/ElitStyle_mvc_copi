<?php
// Inclui a classe de conexão com o banco de dados
require_once __DIR__ . '/../db/database.php';

// Instancia a classe Database e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

// Inclui o arquivo de configuração do Mercado Pago
$mercadoPagoConfigPath = __DIR__ . '/../mercadopago_config.php';

if (!file_exists($mercadoPagoConfigPath)) {
    die("Erro: O arquivo mercadopago_config.php não foi encontrado em: " . $mercadoPagoConfigPath);
}

require_once $mercadoPagoConfigPath;

// Verifica se os dados foram enviados pelo formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário via POST
    $numero = $_POST['numero'];
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $infoAdicional = $_POST['infoAdicional'];
    $id_user_db1 = $_POST['id_user'];

    try {
        // Verifica se o endereço já existe para o usuário
        $query = "SELECT id_endereco FROM endereco WHERE id_user_db1 = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_user_db1);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Se já existe um endereço, faz o UPDATE
            $query = "UPDATE endereco SET numero = ?, rua = ?, bairro = ?, cep = ?, estado = ?, cidade = ?, informacoes_adicionais = ? WHERE id_user_db1 = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $infoAdicional, $id_user_db1);

            if ($stmt->execute()) {
                header("Location: ../../Views/login.html?message=endereco_atualizado");
                exit();
            } else {
                throw new Exception("Erro ao atualizar o endereço: " . $stmt->error);
            }
        } else {
            // Se não existe, faz o INSERT
            $query = "INSERT INTO endereco (numero, rua, bairro, cep, estado, cidade, informacoes_adicionais, id_user_db1) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $infoAdicional, $id_user_db1);

            if ($stmt->execute()) {
                header("Location: ../../Views/login.html?message=endereco_cadastrado");
                exit();
            } else {
                throw new Exception("Erro ao cadastrar o endereço: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        // Fecha a conexão com o banco de dados
        $stmt->close();
        $conn->close();
    }

    // Cria um objeto de pagamento
    try {
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = 100;
        $payment->description = "Título do produto";
        $payment->payment_method_id = "pix";
        $payment->payer = array(
            "email" => "test@test.com"
        );

        $payment->save();
    } catch (Exception $e) {
        echo "Erro ao criar o pagamento: " . $e->getMessage();
    }

    // Aqui você pode redirecionar o usuário ou mostrar informações do pagamento
}
