<?php
session_start();

// Exibe mensagens de erro/sucesso
if (isset($_SESSION['error'])) {
    echo '<p style="color:red; font-weight:bold; text-align:center;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<p style="color:green; font-weight:bold; text-align:center;">' . htmlspecialchars($_SESSION['success']) . '</p>';
    unset($_SESSION['success']);
}

$conn = include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['Nome']);
    $email = trim($_POST['Email']);
    $telefone = $_POST['TELEFONE'];
    $cpf = $_POST['cpf'];
    $datanasc = $_POST['datanasc'];
    $modalidade = $_POST['modalidade'];
    $curso = $_POST['curso'];

if (!empty($datanasc)) {
    $datanasc = date('Y-m-d', strtotime(str_replace('/', '-', $datanasc)));
}


    // Verifica campos obrigatórios
    if (empty($nome) || empty($email) || empty($telefone) || empty($cpf) || empty($datanasc) || empty($modalidade) || empty($curso)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios.";
        header("Location: index.php");
        exit;
    }

    // Verifica se CPF já existe
    $stmt = $conn->prepare("SELECT ID_Usuario FROM tbl_usuario WHERE CPF = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Este CPF já está cadastrado.";
        header("Location: index.php");
        exit;
    }

    // INSERÇÃO
    $stmt = $conn->prepare("
        INSERT INTO tbl_usuario 
        (Nome, Email, Telefone, CPF, DataNasc, Modalidade, Curso)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    // ⚠️ Correção importante:
    // s = string | data também é string no MySQL | nunca use "d" para datas
    $stmt->bind_param("sssssss", $nome, $email, $telefone, $cpf, $datanasc, $modalidade, $curso);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Cadastrado com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UNINOVE - Inscreva-se Agora!</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php include 'Topo.php'; ?>

<body>
    <header>
        <div class="header-container">
            <div class="header-content">
                <img alt="Logo da UNINOVE" src="foto transparente.png" />
                <h1>SEU FUTURO COMEÇA AQUI.</h1>
                <p>Graduação a partir de R$ 99/mês. Inscreva-se e mude seu futuro!</p>
            </div>
            <div class="form-container">
                <div id="form-wrapper">
                    <h2>Comece seu futuro hoje!</h2>
                    <form id="lead-form" action="" method="post">
                        <div>
                            <label for="name">Nome Completo</label>
                            <input id="name" name="Nome" placeholder="Seu nome" required="" type="text" />
                            <p class="hidden" id="name-error">Por favor, preencha seu nome.</p>
                        </div>
                        <div>
                            <label for="email">Seu melhor e-mail</label>
                            <input id="email" name="Email" placeholder="seu.email@exemplo.com" required=""
                                type="email" />
                            <p class="hidden" id="email-error">Por favor, preencha seu e-mail.</p>
                        </div>
                        <div>
                            <label for="phone">Telefone (WhatsApp)</label>
                            <input id="phone" name="TELEFONE" placeholder="(11) 99999-9999" required="" type="tel" />
                            <p class="hidden" id="phone-error">Por favor, preencha seu telefone.</p>
                        </div>
                        <div>
                            <label for="cpf">CPF</label>
                            <input id="cpf" name="cpf" placeholder="000.000.000-00" required="" type="text" />
                            <p class="hidden" id="cpf-error">Por favor, preencha seu CPF.</p>
                        </div>
                        <div>
                            <label for="data_nascimento">Data de Nascimento</label>
                            <input id="data_nascimento" name="datanasc" required="" type="date" />
                            <p class="hidden" id="data_nascimento-error">Por favor, preencha a data de nascimento.</p>
                        </div>
                        <div>
                            <label for="modalidade">Modalidade</label>
                            <select id="modalidade" name="modalidade" required="">
                                <option disabled="" selected="" value="">Selecione a Modalidade</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Semipresencial">Semipresencial (Híbrido)</option>
                                <option value="EAD">EAD</option>
                            </select>
                            <p class="hidden" id="modalidade-error">Por favor, selecione uma modalidade.</p>
                        </div>
                        <div>
                            <label for="course">Curso de Interesse</label>
                            <select id="course" name="curso" required="">
                                <option disabled="" selected="" value="">Selecione o curso</option>
                                <optgroup data-modalidade="Presencial" label="Presencial">
                                    <option value="Administracao">Administração - Bacharelado - 8 Semestres</option>
                                    <option value="Analise e Desenvolvimento de Sistemas">Análise e Desenvolvimento de
                                        Sistemas - Tecnólogo - 5 semestres</option>
                                    <option value="Ciencia Contabeis">Ciência Contábeis - Tecnólogo - 8 semestres
                                    </option>
                                    <option value="Direito">Direito - Bacharelado - 10 semestres</option>
                                    <option value="Educacao Fisica Bacharelado">Educação Física - Bacharelado - 8
                                        semestres</option>
                                    <option value="Educacao Fisica Licenciatura">Educação Física - Licenciatura - 8
                                        semestres</option>
                                    <option value="Enfermagem">Enfermagem - Bacharelado - 10 semestres</option>
                                    <option value="Engenharia Civil">Engenharia Cívil - Bacharelado - 10 Semestres
                                    </option>
                                    <option value="Engenharia Computacao">Engenharia Computação - Bacharelado - 10
                                        Semestres</option>
                                    <option value="Engenharia de Controle da Automacao">Engenharia de Controle da
                                        Automação - Bacharelado - 10 Semestres</option>
                                    <option value="Engenharia Eletrica">Engenharia Elétrica - Bacharelado - 10 Semestres
                                    </option>
                                    <option value="Engenharia Mecanica">Engenharia Mecânica - Bacharelado - 10 Semestres
                                    </option>
                                    <option value="Farmacia">Farmácia - Bacharelado - 10 Semestres</option>
                                    <option value="Fisioterapia">Fisioterapia - Bacharelado - 10 Semestres</option>
                                    <option value="Gestao da Tecnologia da Informacao">Gestão da Tecnologia da
                                        Informação - Tecnólogo - 4 semestres</option>
                                    <option value="Gestao de Recursos Humanos">Gestão de Recursos Humanos - Tecnólogo -
                                        4 semestres</option>
                                    <option value="Gestao Financeira">Gestão Financeira - Tecnólogo - 4 semestres
                                    </option>
                                    <option value="Logistica">Logística - Tecnólogo - 4 semestres</option>
                                    <option value="Marketing">Marketing - Tecnólogo - 4 semestres</option>
                                    <option value="Nutricao">Nutrição - Tecnólogo - 8 semestres</option>
                                    <option value="Odontologia">Odontologia - Bacharelado - 10 semestres</option>
                                    <option value="Pedagogia">Pedagogia - Licenciatura - 8 semestres</option>
                                    <option value="Psicologia">Psicologia - Bacharelado - 10 semestres</option>
                                    <option value="Publicidade e Propaganda">Publicidade e Propaganda - Bacharelado - 8
                                        semestres</option>
                                    <option value="Sistemas de Informacao">Sistemas de Informação - Bacharelado - 8
                                        semestres</option>
                                </optgroup>
                                <optgroup data-modalidade="Semipresencial" label="Semipresencial">
                                    <option value="Arquitetura e Urbanismo">Arquitetura e Urbanismo - Bacharelado - 10
                                        semestres</option>
                                    <option value="Biomedicina">Biomedicina - Bacharelado - 10 semestres</option>
                                    <option value="Ciencias Biologicas">Ciências Biológicas - Licenciatura - 8 semestres
                                    </option>
                                    <option value="Educacao Fisica Bacharelado">Educação Física - Bacharelado - 8
                                        semestres</option>
                                    <option value="Educacao Fisica Licenciatura">Educação Física - Licenciatura - 8
                                        semestres</option>
                                    <option value="Engenharia Civil">Engenharia Civil - Bacharelado - 10 semestres
                                    </option>
                                    <option value="Engenharia Eletrica">Engenharia Elétrica - Bacharelado - 10 semestres
                                    </option>
                                    <option value="Engenharia Mecanica">Engenharia Mecânica - Bacharelado - 10 semestres
                                    </option>
                                    <option value="Estetica e Cosmetica">Estética e Cosmética - Tecnólogo - 5 semestres
                                    </option>
                                    <option value="Fisioterapia">Fisioterapia - Bacharelado - 10 semestres</option>
                                    <option value="Letras, Portugues">Letras, Português - Licenciatura - 8 semestres
                                    </option>
                                    <option value="Letras, Portugues e Espanhol">Letras, Português e Espanhol -
                                        Licenciatura - 8 semestres</option>
                                    <option value="Letras, Portugues e Ingles">Letras, Português e Inglês - Licenciatura
                                        - 8 semestres</option>
                                    <option value="Nutricao">Nutrição - Bacharelado - 8 semestres</option>
                                    <option value="Pedagogia">Pedagogia - Licenciatura - 8 semestres</option>
                                </optgroup>
                                <optgroup data-modalidade="EAD" label="EAD">
                                    <option value="Administracao">Administração - 8 Semestres</option>
                                    <option value="Administracao Publica">Administração Pública - 8 Semestres</option>
                                    <option value="Analise e Desenvolvimento de Sistemas">Análise e Desenvolvimento de
                                        Sistemas - 5 semestres</option>
                                    <option value="Arquitetura de Dados">Arquitetura de Dados - 5 Semestres</option>
                                    <option value="Blockchain Criptomoedas e Financas na Era Digital">Blockchain,
                                        Criptomoedas e Finanças na Era Digital - 4 Semestres</option>
                                    <option value="Ciberseguranca">Cibersegurança - 5 semestres</option>
                                    <option value="Ciencia Politica">Ciência Política - 7 Semestres</option>
                                    <option value="Ciencia da Computacao">Ciência da Computação - 8 Semestres</option>
                                    <option value="Ciencia de Dados">Ciência de Dados - 5 semestres</option>
                                    <option value="Ciencias Contabeis">Ciência Contábeis - 8 semestres</option>
                                    <option value="Ciencias Economicas">Ciências Econômicas - 8 semestres</option>
                                    <option value="Coaching e Desenvolvimento Humano">Coaching e Desenvolvimento Humano
                                        - 4 semestres</option>
                                    <option value="Computacao em Nuvem">Computação em Nuvem - 5 semestres</option>
                                    <option value="Comercio Exterior">Comércio Exterior - 4 semestres</option>
                                    <option value="Criminologia">Criminologia - 6 semestres</option>
                                    <option value="Desenvolvimento Mobile">Desenvolvimento Mobile - 4 semestres</option>
                                    <option value="Desenvolvimento Web">Desenvolvimento Web - 4 semestres</option>
                                    <option value="Design Grafico">Design Gráfico - 4 semestres</option>
                                    <option value="Design de Interiores">Design de Interiores - 4 semestres</option>
                                    <option value="Design de Moda">Design de Moda - 4 semestres</option>
                                    <option value="DevOps">DevOps - 4 semestres</option>
                                    <option value="Empreendedorismo">Empreendedorismo - 4 semestres</option>
                                    <option value="Empreendedorismo e Novos Negocios">Empreendedorismo e Novos Negócios
                                        - 4 semestres</option>
                                    <option value="Fotografia">Fotografia - 4 semestres</option>
                                    <option value="Gastronomia">Gastronomia - 4 semestres</option>
                                    <option value="Gerontologia">Gerontologia - 4 semestres</option>
                                    <option value="Gestao Ambiental">Gestão Ambiental - 4 semestres</option>
                                    <option value="Gestao Comercial">Gestão Comercial - 4 semestres</option>
                                    <option value="Gestao Financeira">Gestão Financeira - 4 semestres</option>
                                    <option value="Gestao Hospitalar">Gestão Hospitalar - 6 semestres</option>
                                    <option value="Gestao Portuaria">Gestão Portuária - 6 semestres</option>
                                    <option value="Gestao Publica">Gestão Pública - 4 semestres</option>
                                    <option value="Gestao da Inovacao">Gestão da Inovação - 4 semestres</option>
                                    <option value="Gestao da Producao Industrial">Gestão da Produção Industrial - 6
                                        semestres</option>
                                    <option value="Gestao da Qualidade">Gestão da Qualidade - 4 semestres</option>
                                    <option value="Gestao da Tecnologia da Informacao">Gestão da Tecnologia da
                                        Informação - 5 semestres</option>
                                    <option value="Gestao de Cooperativas">Gestão de Cooperativas - 4 semestres</option>
                                    <option value="Gestao de Produto">Gestão de Produto - 4 semestres</option>
                                    <option value="Gestao de Recursos Humanos">Gestão de Recursos Humanos - 4 semestres
                                    </option>
                                    <option value="Gestao de Saude Publica">Gestão de Saúde Pública - 4 semestres
                                    </option>
                                    <option value="Gestao de Seguranca Privada">Gestão de Segurança Privada - 4
                                        semestres</option>
                                    <option value="Gestao de Turismo">Gestão de Turismo - 4 semestres</option>
                                    <option value="Inteligencia de Mercado e Analise de Dados">Inteligência de Mercado e
                                        Análise de Dados - 5 semestres</option>
                                    <option value="Investigacao e Pericia Criminal">Investigação e Perícia Criminal - 4
                                        semestres</option>
                                    <option value="Jogos Digitais">Jogos Digitais - 5 semestres</option>
                                    <option value="Jornalismo">Jornalismo - 8 semestres</option>
                                    <option value="Logistica">Logística - 4 semestres</option>
                                    <option value="Marketing">Marketing - 4 semestres</option>
                                    <option value="Marketing Digital">Marketing Digital - 4 semestres</option>
                                    <option value="Mediacao">Mediação - 4 semestres</option>
                                    <option value="Negocios Imobiliarios">Negócios Imobiliários - 4 semestres</option>
                                    <option value="Podologia">Podologia - 4 semestres</option>
                                    <option value="Processos Gerenciais Tecnologo">Processos Gerenciais Tecnólogo - 4
                                        semestres</option>
                                    <option value="Producao Audiovisual Tecnologo">Produção Audiovisual Tecnólogo - 4
                                        semestres</option>
                                    <option value="Psicopedagogia Bacharelado">Psicopedagogia Bacharelado - 8 semestres
                                    </option>
                                    <option value="Publicidade e Propaganda Bacharelado">Publicidade e Propaganda
                                        Bacharelado - 8 semestres</option>
                                    <option value="Radiologia Tecnologo">Radiologia Tecnólogo - 6 semestres</option>
                                    <option value="Redes de Computadores Tecnologo">Redes de Computadores Tecnólogo - 5
                                        semestres</option>
                                    <option value="Relacoes Internacionais Bacharelado">Relações Internacionais
                                        Bacharelado - 8 semestres</option>
                                    <option value="Secretariado Tecnologo">Secretariado Tecnólogo - 4 semestres</option>
                                    <option value="Seguranca Publica Tecnologo">Segurança Pública Tecnólogo - 4
                                        semestres</option>
                                    <option value="Seguranca da Informacao Tecnologo">Segurança da Informação Tecnólogo
                                        - 5 semestres</option>
                                    <option value="Servico Social Bacharelado">Serviço Social Bacharelado - 8 semestres
                                    </option>
                                    <option value="Servicos Juridicos Cartorarios e Notariais Tecnologo">Serviços
                                        Jurídicos Cartorários e Notariais Tecnólogo - 4 semestres</option>
                                    <option value="Sistemas de Informacao Bacharelado">Sistemas de Informação
                                        Bacharelado - 8 semestres</option>
                                    <option value="Sistemas para Internet Tecnologo">Sistemas para Internet Tecnólogo -
                                        5 semestres</option>
                                    <option value="Teologia Bacharelado">Teologia Bacharelado - 8 semestres</option>
                                    <option value="Terapias Integrativas e Complementares Tecnologo">Terapias
                                        Integrativas e Complementares Tecnólogo - 4 semestres</option>
                                </optgroup>
                            </select>
                            <p class="hidden" id="course-error">Por favor, selecione um curso.</p>
                        </div>
                        <button type="submit">
                            QUERO SABER MAIS!
                        </button>
                    </form>
                    <p>Seus dados estão seguros. Ao enviar, você concorda em receber contato da nossa equipe.</p>
                </div>
                <div class="hidden" id="success-message">
                    <h2>Inscrição recebida!</h2>
                    <p>Agradecemos seu interesse. Um de nossos consultores entrará em contato em breve pelo WhatsApp ou
                        e-mail fornecido. Boa sorte na sua jornada!</p>
                </div>
            </div>
        </div>
    </header>

    <section class="features-section">
        <div class="features-container">
            <h2>Por que escolher a UNINOVE?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Flexibilidade de Horários</h3>
                    <p>Estude no seu ritmo, com aulas online, semipresenciais ou presenciais, que se encaixam na sua
                        rotina.</p>
                </div>
                <div class="feature-card">
                    <h3>Corpo Docente Qualificado</h3>
                    <p>Aprenda com professores experientes e atuantes no mercado, prontos para te preparar para o
                        futuro.</p>
                </div>
                <div class="feature-card">
                    <h3>Infraestrutura Moderna</h3>
                    <p>Acesse laboratórios de ponta, bibliotecas e ambientes de estudo ideais para o seu aprendizado.
                    </p>
                </div>
                <div class="feature-card">
                    <h3>Ampla Oferta de Cursos</h3>
                    <p>Escolha entre diversas áreas e modalidades, encontrando o curso ideal para a sua carreira.</p>
                </div>
                <div class="feature-card">
                    <h3>Reconhecimento no Mercado</h3>
                    <p>A UNINOVE é reconhecida por sua qualidade de ensino, valorizando seu diploma no mercado de
                        trabalho.</p>
                </div>
                <div class="feature-card">
                    <h3>Preços Acessíveis e Bolsas</h3>
                    <p>Estude em uma universidade de qualidade com mensalidades que cabem no seu bolso e diversas opções
                        de bolsa.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <p>© 2025 UNINOVE Educacional. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>