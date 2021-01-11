<footer class="app-footer">
    <div class="site-footer-right">

            {!! __('voyager::theme.footer_copyright') !!} <a href="#" target="_blank">Emami Group</a>

        @php $version = Voyager::getVersion(); @endphp
        @if (!empty($version))
            - v1.0
        @endif
    </div>
</footer>
