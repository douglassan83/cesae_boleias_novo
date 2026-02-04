@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <div class="text-center mb-5">
<h1 class="fw-bold">Precisa de ajuda?</h1>
<p class="lead">
            Esclareça tudo aqui — aceda às perguntas mais frequentes e fique informado.
</p>
<p>
            Na página de Perguntas Frequentes (FAQ) pode encontrar respostas claras para as dúvidas mais comuns
            sobre o funcionamento da plataforma de boleias do Cesae Digital.
            Consulte esta secção sempre que precisar de orientação rápida ou informações adicionais.
</p>
</div>

    {{-- FAQ LISTA --}}
<div class="accordion" id="faqAccordion">

        {{-- SOBRE A PLATAFORMA --}}
<h4 class="mt-4 mb-3">🚗 Sobre a Plataforma</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq1',
            'pergunta' => '1. O que é a plataforma de boleias do Cesae Digital?',
            'resposta' => 'É uma plataforma criada para facilitar a partilha de boleias entre formandos do Cesae Digital, promovendo mobilidade, economia e sustentabilidade.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq2',
            'pergunta' => '2. Quem pode utilizar a plataforma?',
            'resposta' => 'Todos os formandos, ex-formandos e colaboradores do Cesae Digital com e-mail institucional @msft.cesae.pt.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq3',
            'pergunta' => '3. A plataforma é gratuita?',
            'resposta' => 'Sim. A utilização é totalmente gratuita. Apenas poderá haver partilha de custos de combustível entre condutor e passageiros.'
        ])


        {{-- CONTA E ACESSO --}}
<h4 class="mt-5 mb-3">👤 Conta e Acesso</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq4',
            'pergunta' => '4. Como faço o registo?',
            'resposta' => 'Basta aceder à página de registo, inserir os seus dados e escolher se pretende ser motorista ou passageiro.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq5',
            'pergunta' => '5. Posso usar o meu e-mail pessoal?',
            'resposta' => 'Não. Apenas e-mails institucionais @msft.cesae.pt são aceites para garantir segurança e autenticidade.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq6',
            'pergunta' => '6. O que faço se tiver problemas ao iniciar sessão?',
            'resposta' => 'Pode redefinir a palavra-passe ou contactar o suporte técnico do Cesae Digital.'
        ])


        {{-- OFERECER E PROCURAR BOLEIAS --}}
<h4 class="mt-5 mb-3">🚘 Oferecer e Procurar Boleias</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq7',
            'pergunta' => '7. Como posso oferecer uma boleia?',
            'resposta' => 'Se for motorista, basta aceder ao menu “Criar Boleia”, preencher os dados e publicar.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq8',
            'pergunta' => '8. Como encontro boleias disponíveis?',
            'resposta' => 'Na página principal de boleias, pode filtrar e visualizar todas as boleias ativas.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq9',
            'pergunta' => '9. Posso combinar boleias recorrentes?',
            'resposta' => 'Sim. Motoristas podem criar boleias diárias ou semanais, e passageiros podem solicitar sempre que necessário.'
        ])


        {{-- CUSTOS E PAGAMENTOS --}}
<h4 class="mt-5 mb-3">💸 Custos e Pagamentos</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq10',
            'pergunta' => '10. As boleias são pagas?',
            'resposta' => 'Não existe pagamento obrigatório. O motorista pode sugerir partilha de custos, mas é opcional.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq11',
            'pergunta' => '11. Como é feito o pagamento?',
            'resposta' => 'Qualquer acordo financeiro é combinado diretamente entre motorista e passageiros.'
        ])


        {{-- SEGURANÇA E PRIVACIDADE --}}
<h4 class="mt-5 mb-3">🔒 Segurança e Privacidade</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq12',
            'pergunta' => '12. Como a plataforma garante segurança?',
            'resposta' => 'Apenas utilizadores autenticados com e-mail institucional podem participar, garantindo confiança e segurança.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq13',
            'pergunta' => '13. As minhas informações pessoais ficam visíveis?',
            'resposta' => 'Apenas dados essenciais são partilhados com motoristas e passageiros envolvidos na boleia.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq14',
            'pergunta' => '14. Posso denunciar um utilizador?',
            'resposta' => 'Sim. Existe um sistema de denúncia e avaliação para garantir bom comportamento.'
        ])


        {{-- AVALIAÇÕES --}}
<h4 class="mt-5 mb-3">⭐ Avaliações e Comportamento</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq15',
            'pergunta' => '15. Como funcionam as avaliações?',
            'resposta' => 'Após cada boleia, passageiros e motoristas podem avaliar a experiência.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq16',
            'pergunta' => '16. O que acontece se um utilizador tiver avaliações negativas?',
            'resposta' => 'A equipa do Cesae Digital pode intervir e aplicar medidas, incluindo suspensão da conta.'
        ])


        {{-- SUPORTE --}}
<h4 class="mt-5 mb-3">🛠️ Suporte e Problemas</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq17',
            'pergunta' => '17. O que faço se o condutor cancelar a boleia?',
            'resposta' => 'Será notificado e poderá procurar outra boleia disponível.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq18',
            'pergunta' => '18. Como reporto um problema técnico?',
            'resposta' => 'Pode contactar o suporte técnico através da área de ajuda.'
        ])

        @include('utils.partials.faq-item', [
            'id' => 'faq19',
            'pergunta' => '19. Posso sugerir melhorias para a plataforma?',
            'resposta' => 'Sim. A plataforma está em constante evolução e sugestões são bem-vindas.'
        ])


        {{-- FUNCIONAMENTO GERAL --}}
<h4 class="mt-5 mb-3">📅 Funcionamento Geral</h4>

        @include('utils.partials.faq-item', [
            'id' => 'faq20',
            'pergunta' => '20. Posso usar a plataforma fora do horário das aulas?',
            'resposta' => 'Sim. A plataforma está disponível 24 horas por dia.'
        ])

    </div>
</div>
@endsection
