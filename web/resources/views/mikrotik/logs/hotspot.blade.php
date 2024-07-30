{{-- <div class="row">
    <div class="col-lg-12"> --}}
        <div class="box box-primary">
            <div class="box-body">
                <table id="tbMikrotikLog" class="display" width="100%"></table>
            </div>
        </div>
    {{-- </div>
</div> --}}

<script type="text/javascript">
    $(document).ready(function() {
        // -- Logs --
        var listLog = {!! json_encode($logs) !!};
        $('#tbMikrotikLog').DataTable({
            data: listLog,
            order: [
                [0, "desc"]
            ],
            columns: [{
                    title: "Time",
                    data: "time",
                    order: "desc"
                },
                {
                    title: "User",
                    data: "user"
                },
                {
                    title: "Message",
                    data: "message"
                },
            ]
        });
        // .. Logs ..
    });
</script>
