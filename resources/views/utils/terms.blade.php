@extends('layouts.main_layout')

@section('content')
<br>

<div class="container" style="max-width: 800px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h3 class="mb-3 text-primary">Termos de Uso e Responsabilidade</h3>
            <p class="text-muted">Última atualização: {{ date('d/m/Y') }}</p>

            <hr>

            <h5 class="mt-4">1. Introdução</h5>
            <p>
                O <strong>CESAE Boleias</strong> é uma plataforma criada com o objetivo de facilitar a partilha de transporte
                entre alunos, formadores e colaboradores do CESAE Digital. O uso da plataforma implica a aceitação integral
                destes Termos de Uso e Responsabilidade.
            </p>

            <h5 class="mt-4">2. Responsabilidades do Utilizador</h5>
            <p>
                O utilizador compromete-se a:
            </p>
            <ul>
                <li>Fornecer informações verdadeiras e atualizadas no momento do registo.</li>
                <li>Utilizar a plataforma de forma ética, respeitosa e segura.</li>
                <li>Não utilizar o serviço para fins ilegais, abusivos ou que coloquem terceiros em risco.</li>
            </ul>

            <h5 class="mt-4">3. Responsabilidades do Motorista</h5>
            <p>
                O motorista declara que:
            </p>
            <ul>
                <li>Possui carta de condução válida e dentro da legalidade.</li>
                <li>O veículo utilizado está legalizado, com inspeção válida e seguro obrigatório.</li>
                <li>Conduzirá de forma segura, responsável e em conformidade com o Código da Estrada.</li>
            </ul>

            <h5 class="mt-4">4. Limitação de Responsabilidade</h5>
            <p>
                O CESAE Boleias atua apenas como intermediário entre utilizadores e não se responsabiliza por:
            </p>
            <ul>
                <li>Acidentes, danos materiais ou pessoais ocorridos durante o transporte.</li>
                <li>Condutas inadequadas, atrasos, cancelamentos ou conflitos entre utilizadores.</li>
                <li>Informações falsas fornecidas por motoristas ou passageiros.</li>
            </ul>

            <h5 class="mt-4">5. Privacidade e Dados</h5>
            <p>
                Os dados fornecidos são utilizados exclusivamente para funcionamento da plataforma. Nenhuma informação é
                partilhada com terceiros, exceto quando exigido por lei.
            </p>

            <h5 class="mt-4">6. Aceitação dos Termos</h5>
            <p>
                Ao registar-se e utilizar a plataforma, o utilizador confirma que leu, compreendeu e concorda com estes
                Termos de Uso e Responsabilidade.
            </p>

            <hr>

            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Voltar</a>

        </div>
    </div>
</div>

<br>
@endsection
