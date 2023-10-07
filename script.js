$(document).ready(function () {
    $(".task-row").hover(function () {
        const startDate = $(this).data("start-date");
        const deadline = $(this).data("deadline");
        const taskDescription = $(this).find("td:eq(1)").text();
        const taskPreview = "Task: " + taskDescription + "<br>Start Date: " + startDate + "<br>Deadline: " + deadline;

        const elementPosition = $(this).offset();
        const elementHeight = $(this).height();

        const popupTop = elementPosition.top - elementHeight - $(".task-preview").height() - 10;
        const popupLeft = elementPosition.left;

        $(".task-preview").html(taskPreview).css({
            "top": popupTop,
            "left": popupLeft
        }).show();
    }, function () {
        $(".task-preview").hide();
    });

    $("#calendar").datepicker({
        dateFormat: "yy-mm-dd",
        beforeShowDay: function (date) {
            const startDate = new Date($(".task-preview").find("Start Date").text());
            const deadline = new Date($(".task-preview").find("Deadline").text());

            if (date >= startDate && date <= deadline) {
                return [true, "highlighted", "Task Duration"];
            } else {
                return [true, "", ""];
            }
        }
    });

    $(".task-row").on("click", function () {
        $(".calendar-popup").css({
            "top": $(this).offset().top + $(this).height() + 10,
            "left": $(this).offset().left
        }).show();
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest(".calendar-popup").length) {
            $(".calendar-popup").hide();
        }
    });
});
