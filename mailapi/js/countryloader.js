$(document).ready(function () {
    //load_overlay();
    $.ajax({
        url: "mailapi/json/country.txt",
        dataType: "text",
        success: function (data) {
            var jsonData = JSON.parse(data);
            $.each(jsonData, function (i, row) {

                $("#ddl_country").append('<option value=' + row.id + '>' + row.name + '</option>');

            });
            //close_overlay();
        },
        error: function (xhr, status) {
           // close_overlay();
        }
    });

    $(document).on('change', '#ddl_country', function () {
        //load_overlay();
        if (parseInt($("#ddl_country").val()) != 0) {
            $.ajax({
                url: "mailapi/json/country.txt",
                dataType: "text",
                success: function (data) {
                    var jsonData = JSON.parse(data);
                    $.each(jsonData, function (i, row) {
                        if (parseInt(row.id) == $("#ddl_country").val()) {
                            $('#hdn_countrycode').val(row.phone_code);
                            //$('#hdn_countryname').val($('#ddl_country option:selected').text());
                            $('#hdn_countryname').val($('#ddl_country option:selected').text());
                            //alert($('#hdn_countryname').val());
                            close_overlay();
                            return;
                        }
                    });
                    //close_overlay();
                },
                error: function (xhr, status) {
                    close_overlay();
                }
            });

        }
    });


});