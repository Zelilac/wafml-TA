$(document).ready(function () {
  var current_fs, next_fs, previous_fs

  function validateStep(current_fs, next_fs, skip_index = "") {
    var current_index = $(".card2").index(current_fs)
    var next_index = $(".card2").index(next_fs)

    var allFilled = true

    if (skip_index == "1" || next_index > current_index) {
      // cek input teks, textarea
      current_fs
        .find(
          "input[type='text'][required], input[type='number'][required], textarea[required]"
        )
        .each(function () {
          // cek pakai Inputmask jika ada
          if ($(this).data("inputmask")) {
            if (!$(this).inputmask("isComplete")) {
              allFilled = false
              $(this).addClass("is-invalid")
            } else {
              $(this).removeClass("is-invalid")
            }
          } else {
            // cek biasa
            if ($(this).val().trim() === "") {
              allFilled = false
              $(this).addClass("is-invalid")
            } else {
              $(this).removeClass("is-invalid")
            }
          }
        })

      // cek radio dan checkbox
      current_fs
        .find("input[type='radio'][required], input[type='checkbox'][required]")
        .each(function () {
          var name = $(this).attr("name")
          var group = current_fs.find("input[name='" + name + "']")
          if (group.filter(":checked").length === 0) {
            allFilled = false
            group.addClass("is-invalid") // kasih invalid ke semua radio/checkbox dalam group
          } else {
            group.removeClass("is-invalid") // hapus invalid kalau ada yg dipilih
          }
        })

      // cek select
      current_fs.find("select[required]").each(function () {
        if ($(this).val() === null || $(this).val() === "") {
          allFilled = false
          $(this).addClass("is-invalid")
        } else {
          $(this).removeClass("is-invalid")
        }
      })

      // alert("Harap lengkapi semua field di step ini!")
    }

    if (!allFilled) {
      showToastr("error", "Mohon Tentukan Terlebih Dahulu Pilihan Anda")
    }

    return allFilled
  }

  $(".step0-trigger").click(function () {
    var next_step = $(this).index()

    current_fs = $(".card2.show")
    next_fs = $(".card2").eq(next_step)

    if (!validateStep(current_fs, next_fs)) {
      return false
    }

    $(current_fs).removeClass("show")
    $(next_fs).addClass("show")

    for (var i = 0; i <= parseInt($(".step0-trigger").length - 1); i++) {
      if (i <= next_step) {
        if (!$("#progressbar li").eq(i).hasClass("active")) {
          $("#progressbar li").eq(i).addClass("active")
        }
      } else {
        if ($("#progressbar li").eq(i).hasClass("active")) {
          $("#progressbar li").eq(i).removeClass("active")
        }
      }
    }

    current_fs.animate(
      {},
      {
        step: function () {
          current_fs.css({
            display: "none",
            position: "relative",
          })

          next_fs.css({
            display: "block",
          })
        },
      }
    )

    const id = $(this).attr("id")

    const hash = "#" + id.replace("-li", "")

    // Ganti hash di URL (tanpa reload)
    window.location.hash = hash
  })

  $(".step-text-trigger").click(function () {
    var next_step = parseInt($(this).index() - 1)

    current_fs = $(".card2.show")
    next_fs = $(".card2").eq(next_step)

    if (!validateStep(current_fs, next_fs)) {
      return false
    }

    $(current_fs).removeClass("show")
    $(next_fs).addClass("show")

    for (var i = 0; i <= parseInt($(".step-text-trigger").length - 1); i++) {
      if (i <= next_step) {
        if (!$("#progressbar li").eq(i).hasClass("active")) {
          $("#progressbar li").eq(i).addClass("active")
        }
      } else {
        if ($("#progressbar li").eq(i).hasClass("active")) {
          $("#progressbar li").eq(i).removeClass("active")
        }
      }
    }

    current_fs.animate(
      {},
      {
        step: function () {
          current_fs.css({
            display: "none",
            position: "relative",
          })

          next_fs.css({
            display: "block",
          })
        },
      }
    )

    const id = $(this).attr("id")

    const hash = "#" + id.replace("-h6", "")

    // Ganti hash di URL (tanpa reload)
    window.location.hash = hash
  })

  // Next button
  $(".next-button").click(function () {
    current_fs = $(this).parent().parent().parent()
    next_fs = $(this).parent().parent().parent().next()

    if (!validateStep(current_fs, next_fs, 1)) {
      return false
    }

    $(current_fs).removeClass("show")
    $(next_fs).addClass("show")

    $("#progressbar li").eq($(".card2").index(next_fs)).addClass("active")

    current_fs.animate(
      {},
      {
        step: function () {
          current_fs.css({
            display: "none",
            position: "relative",
          })

          next_fs.css({
            display: "block",
          })
        },
      }
    )
  })

  // Previous button
  $(".prev-button").click(function () {
    current_fs = $(".card2.show")
    previous_fs = $(".card2.show").prev()

    $(current_fs).removeClass("show")
    $(previous_fs).addClass("show")

    if ($(".card2.show").hasClass("first-screen")) {
      $(".prev").css({
        display: "none",
      })
    }

    $("#progressbar li").eq($(".card2").index(current_fs)).removeClass("active")

    current_fs.animate(
      {},
      {
        step: function () {
          current_fs.css({
            display: "none",
            position: "relative",
          })

          previous_fs.css({
            display: "block",
          })
        },
      }
    )
  })

  var FormControl = (function () {
    // Variables
    var $input = $(".form-control")
    var $inputLabel = $(".form-control-placeholder")

    // Methods
    function init($this) {
      $this
        .on("mouseenter blur", function (e) {
          $(this)
            .parents(".input-group")
            .addClass("hovered", e.type === "mouseenter")
        })
        .trigger("blur")

      $this
        .on("mouseleave blur", function (e) {
          $(this)
            .parents(".input-group")
            .removeClass("hovered", e.type === "mouseleave")
        })
        .trigger("blur")

      $this
        .on("focus blur", function (e) {
          $(this)
            .parents(".input-group")
            .toggleClass("focused", e.type === "focus")
        })
        .trigger("blur")
    }

    function init2($this) {
      $this
        .on("mouseenter blur", function (e) {
          $(this)
            .parents(".input-group")
            .addClass("hovered", e.type === "mouseenter")
        })
        .trigger("blur")

      $this
        .on("mouseleave blur", function (e) {
          $(this)
            .parents(".input-group")
            .removeClass("hovered", e.type === "mouseleave")
        })
        .trigger("blur")
    }

    // Events
    if ($inputLabel.length) {
      init2($inputLabel)
    }

    if ($input.length) {
      init($input)
    }
  })()

  var myCarousel = document.querySelector("#carouselHeader")
  var carousel = new bootstrap.Carousel(myCarousel, {
    interval: 3000,
  })
})

function showToast(msg) {
  $.toast({
    text: msg,
    heading: "",
    showHideTransition: "fade",
    allowToastClose: false,
    hideAfter: 5000,
    bgColor: "#2D2D2D",
    stack: 5,
    position: "top-right",
    textAlign: "left",
    loader: false,
    loaderBg: "#1572E8",
    beforeShow: function () {},
    afterShown: function () {},
    beforeHide: function () {},
    afterHidden: function () {},
  })
}

function confirmdelete(msg = "") {
  // question = confirm("Apakah anda yakin ingin menghapus " + msg + " ?");
  question = confirm("Apakah anda yakin ingin menghapus data ini?")
  // question = confirm("Are you sure you want to delete " + msg + " ?");
  if (question != "0") {
    return true
  } else {
    return false
  }
}

function showToastify(msg_text, msg_type = "") {
  if (msg_type == "error") {
    var backgroundMsg = "#dc3545"
  } else if (msg_type == "success") {
    var backgroundMsg = "#198754"
  } else {
    var backgroundMsg = "#0d6efd"
  }

  Toastify({
    text: msg_text,
    duration: 5000,
    close: true,
    gravity: "top",
    position: "right",
    backgroundColor: backgroundMsg,
  }).showToast()
}

function showToastr(msg_resp, msg_desc) {
  // toastr.options = {
  //     "closeButton": true, // kasih tombol close
  //     "timeOut": 0, // 0 = tidak auto hilang
  //     "extendedTimeOut": 0 // tidak hilang walau di-hover
  // };

  switch (msg_resp) {
    case "error":
      toastr.error(msg_desc)
      break
    case "success":
      toastr.success(msg_desc)
      break
    default:
      toastr.error(msg_desc)
      break
  }
}
