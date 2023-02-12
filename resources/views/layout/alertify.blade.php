@if (count($errors->all()) > 0)
    <script>
        $(document).ready(function() {
            if (!alertify.errorAlert) {
                alertify.dialog('errorAlert', function factory() {
                    return {
                        build: function() {
                            var errorHeader = '<span data-feather="check-circle" ' +
                                'style="vertical-align:middle;color:#DC3545;">' +
                                '</span> Something Went Wrong';
                            this.setHeader(errorHeader);
                        }
                    };
                }, true, 'alert');
            }
            alertify.errorAlert(
                '<ul class="list-group">' +
                @foreach ($errors->all() as $error)
                    '<li class="list-group-item text-danger border-danger">{{ $error }}</li>' +
                @endforeach
                '</ul>'
            );
        });
    </script>
@endif

@if ($message = Session::get('success'))
    <script>
        // $(document).ready(function() {

        if (!alertify.successAlert) {
            alertify.dialog('successAlert', function factory() {
                return {
                    build: function() {
                        var successHeader = '<span data-feather="check-circle" ' +
                            'style="vertical-align:middle;color:#28A745;">' +
                            '</span> Success';
                        this.setHeader(successHeader);
                    }
                };
            }, true, 'alert');
        }
        alertify.successAlert(
            @if (is_array($message))
                @foreach ($message as $m)
                    '<li>{{ $m }}</li>'
                @endforeach
            @else
                '<h5>{{ $message }}</h5>'
            @endif
        );

        // });
    </script>
@endif

@if ($message = Session::get('danger'))
    <script>
        // $(document).ready(function() {

        if (!alertify.dangerAlert) {
            alertify.dialog('dangerAlert', function factory() {
                return {
                    build: function() {
                        var dangerHeader = '<span data-feather="alert-circle" ' +
                            'style="vertical-align:middle;color:#DC3545;">' +
                            '</span> Danger';
                        this.setHeader(dangerHeader);
                    }
                };
            }, true, 'alert');
        }
        alertify.dangerAlert(
            @if (is_array($message))
                @foreach ($message as $m)
                    '<li>{{ $m }}</li>'
                @endforeach
            @else
                '<h5>{{ $message }}</h5>'
            @endif
        );

        // });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        // $(document).ready(function() {

        if (!alertify.warningAlert) {
            alertify.dialog('warningAlert', function factory() {
                return {
                    build: function() {
                        var warningHeader = '<span data-feather="alert-circle" ' +
                            'style="vertical-align:middle;color:#FFC107;">' +
                            '</span> Warning';
                        this.setHeader(warningHeader);
                    }
                };
            }, true, 'alert');
        }
        alertify.warningAlert(
            @if (is_array($message))
                @foreach ($message as $m)
                    '<li>{{ $m }}</li>'
                @endforeach
            @else
                '<h5>{{ $message }}</h5>'
            @endif
        );

        // });
    </script>
@endif

@if ($message = Session::get('info'))
    <script>
        // $(document).ready(function() {

        if (!alertify.infoAlert) {
            alertify.dialog('infoAlert', function factory() {
                return {
                    build: function() {
                        var infoHeader = '<span data-feather="info" ' +
                            'style="vertical-align:middle;color:#17A2B8;">' +
                            '</span> Information';
                        this.setHeader(infoHeader);
                    }
                };
            }, true, 'alert');
        }
        alertify.infoAlert(
            @if (is_array($message))
                @foreach ($message as $m)
                    '<li>{{ $m }}</li>'
                @endforeach
            @else
                '<h5>{{ $message }}</h5>'
            @endif
        );

        // });
    </script>
@endif
