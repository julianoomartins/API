<!-- resources/views/layouts/partials/footer.blade.php -->

<footer class="main-footer text-center">
    <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.name', 'ADMS 2.0') }}</a>.</strong>
    Todos os direitos reservados.
    <div class="float-right d-none d-sm-inline-block">
        <b>Versão</b> 1.0.0
    </div>
</footer>
