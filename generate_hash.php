<?php
// generate_hash.php
// ACESSE ESTE ARQUIVO PELO NAVEGADOR: https://seu-site.com/generate_hash.php
// Copie o hash exibido e use-o no phpMyAdmin.
// APAGUE ESTE ARQUIVO DO SERVIDOR APÓS O USO!

$password_to_hash = 'admin123';
$hash = password_hash($password_to_hash, PASSWORD_DEFAULT);

echo "<h2>Hash Bcrypt para a senha 'admin123':</h2>";
echo "<p>Copie este valor e cole-o diretamente no campo 'password' do phpMyAdmin para o usuário 'admin', garantindo que a função esteja como 'None' ou vazia.</p>";
echo "<code>" . htmlspecialchars($hash) . "</code>";
echo "<p><strong>ATENÇÃO: APAGUE este arquivo do servidor APÓS UTILIZAR!</strong></p>";
?>
