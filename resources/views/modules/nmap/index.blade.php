@extends('layouts.dashboard.index')
@section('content')
    {{Breadcrumbs::render('nmap.index')}}

<div class="col-span-2">
    <div class="divide-y overflow-hidden rounded-lg shadow border bg-white dark:bg-slate-800 border-slate-200 divide-gray-200 dark:border-slate-700 dark:divide-gray-700">
        <div class="px-4 py-3 sm:px-6 text-center">
            Nmap
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="relative mt-2 rounded-md shadow-sm" style="width: 50%; margin: 0 auto">
                <input type="text" name="search" id="search" class="!ps-10 form-input w-full rounded-md border-0" placeholder="متن مورد نظر" value="">
            </div>

            <div class="m-3" style="width: 50%; margin: 0 auto">
                <button id="search-button" class="btn bg-indigo-500 hover:bg-indigo-600 text-white whitespace-nowrap" type="button">جستجو</button>
            </div>

            <div id="status-message">اطلاعات خود را وارد کنید</div>
            <div id="ping-results"></div>

            <script>
                $(document).ready(function () {
                    // در ابتدا نمایش پیام
                    $('#status-message').text('اطلاعات خود را وارد کنید');

                    $('#search-button').on('click', function () {
                        const inputValue = $('#search').val();
                        if(inputValue.length == 0){
                            $('#status-message').text('فیلد نمیتواند خالی باشد');
                            return;

                        }

                        // تغییر پیام به در حال گرفتن اطلاعات
                        $('#status-message').text('درحال گرفتن اطلاعات...');

                        $.ajax({
                            url: '{{ route('nmap.show', ':value') }}'.replace(':value', inputValue),
                            type: 'GET',
                            // data: { query: inputValue },
                            success: function (data) {
                                const resultDiv = $('#ping-results');
                                resultDiv.html(data);

                                // پیام نهایی با نتیجه
                                $('#status-message').text('نتیجه دریافت شد.');
                            },
                            error: function (xhr, status, error) {
                                $('#ping-results').text('خطا در دریافت داده');
                                $('#status-message').text('خطایی رخ داد.');
                                console.error('Error:', error);
                            }
                        });
                    });
                });

            </script>



        </div>
    </div>
</div>

@endsection

@push('footer')

    <style>
        #ping-results {
            padding: 10px;
            border: 1px solid #ccc;
            font-family: monospace;
            white-space: pre-wrap;
            text-align: left;
            direction: ltr;
        }
    </style>
@endpush

