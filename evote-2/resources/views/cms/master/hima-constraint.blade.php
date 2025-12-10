<section id="content" class="content">
    <div class="content__boxed">
        <div class="content__wrap">
            <div class="card">
                <div class="card-body">
                    <form id="formDataConstraint">
                        @csrf
                        @method('delete')
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;">
                                            <input type="checkbox" id="all-check-constraint" class="form-check-input">
                                        </th>
                                        <th style="width: 3%;">No.</th>
                                        <th style="width: 15%;">NIM</th>
                                        <th style="width: ;">Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getVote as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input row-check-constraint"
                                                    name="id_vote[]" value="{{ $item->id }}">
                                            </td>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->nim }}</td>
                                            <td>{{ optional($item->mahasiswa)->nama ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <div class="mt-4">
                        <button type="button" id="btn-delete-constraint" class="btn btn-danger" onclick="doDeleteConstraint()"
                            disabled>Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var checkAll = document.getElementById("all-check-constraint");
    var deleteBtn = document.getElementById("btn-delete-constraint");
    var rowChecks = document.querySelectorAll(".row-check-constraint");

    // fungsi update tombol hapus
    function updateDeleteButton() {
        var anyChecked = document.querySelectorAll(".row-check-constraint:checked").length > 0;
        deleteBtn.disabled = !anyChecked;
    }

    // event: select all
    checkAll.addEventListener("change", function() {
        rowChecks.forEach(cb => (cb.checked = this.checked));
        updateDeleteButton();
    });

    // event: per-row checkbox
    rowChecks.forEach(cb => {
        cb.addEventListener("change", () => {
            var total = rowChecks.length;
            var checked = document.querySelectorAll(".row-check-constraint:checked").length;

            // update state select all
            checkAll.checked = total === checked;

            updateDeleteButton();
        });
    });
</script>

<script>
    function doDeleteConstraint() {

        Swal.fire({
            text: 'Apakah Anda yakin ingin menghapus Data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1f3574',
            cancelButtonColor: '#b62b39',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.value) {

                var formDocConstraint = document.querySelector('form#formDataConstraint');
                var formDataConstraint = new FormData(formDocConstraint);

                fetch("{{ url($currentRoute) . '/delete-constraint/' . $id }}", {
                        method: "post",
                        body: formDataConstraint
                    })
                    .then(response => response.json())
                    .then((data) => {

                        if (data.msg_resp != 'error') {

                            window.location.reload();
                        } else {

                            showToastr(data.msg_resp, data.msg_desc);
                        }
                    });
            }
        });
    }
</script>
