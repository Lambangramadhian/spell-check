<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spell Checker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('logo-dark.ico') }}">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
</head>

<body>
    @include('header')

    <div class="box-container">
        <!-- input user -->
        <textarea class="box" id="input"></textarea>

        <div class="action-container">
            <div>
                <label for="max_suggestions">Jumlah Saran</label>
                <input type="number" name="max_suggestions" id="max_suggestions" placeholder="">
            </div>
            <div class="buttons">
                <button data-tooltip-label="Cek Ejaan" id="check">
                    <img src="{{asset('images/ballot-check.svg')}}" alt="check list">
                </button>
                <button data-tooltip-label="Simpan perubahan" id="push-changes">
                    <img src="{{asset('images/keyboard-left.svg')}}" alt="push change">
                </button>
                <button data-tooltip-label="Simpan ke papan klip" id="clipboard">
                    <img src="{{asset('images/clipboard.svg')}}" alt="copy">
                </button>
            </div>
        </div>

        <!-- result -->
        <div class="box" id="suggestionsDiv">The result will be shown here!</div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            function refresh_hint_declaration() {
                $('.hint').click(function() {
                    // update the word if hint is clicked
                    console.log($(this).text());
                    let hint_text = $(this).text();
                    let error_elem = $(this).parent().parent();
                    error_elem.text(hint_text).removeClass('typo');
                });
            }

            function push_result_to_input() {
                var userInputField = $('#input');
                var resultField = document.getElementById('suggestionsDiv');

                userInputField.val(resultField.innerText);

                check_spell();
            }

            $('#push-changes').click(function() {
                push_result_to_input()
            });

            // membuat fungsi check_spell
            function check_spell() {
                // mendapatkan isi input
                var userInput = $('#input').val();
                var max_suggestionsv = $('#max_suggestions').val();

                $.ajax({
                    type: 'POST',
                    url: 'spell_check',
                    data: {
                        text: userInput,
                        max_suggestions: max_suggestionsv,
                    },
                    success: function(response) {
                        if (response == '') {
                            response = 'The result will be shown here!'
                        }
                        $('#suggestionsDiv').html(response);

                        refresh_hint_declaration();
                    }
                });
            }

            /**
             * jalankan check menggunakan tombol
             */
            $('#check').on('click', function(e) {
                check_spell();
            });

            /**
             * jalankan check otomatis dari perubahan input
             */
            // membuat timer
            let delayTimer;

            // membuat event listener
            $('#input').bind('input propertychange', function(e) {
                // mereset timer
                clearTimeout(delayTimer);

                // memulai timer
                delayTimer = setTimeout(function() {
                    check_spell();
                }, 500);
            });


        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        $('#clipboard').on('click', function(e) {
            element = $('#suggestionsDiv');
            copyToClipboard(element);
        });
    </script>
</body>

</html>