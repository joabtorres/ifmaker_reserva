/**
 *
 * @author Joab Torres Alencar
 * @description Só carrega o conteudo da página após seu total carregamento
 */
function mostrarConteudo() {
   var elemento = document.getElementById("tela_load");
   elemento.style.display = "none";
   var elemento = document.getElementById("tela_sistema");
   if (elemento) {
      elemento.style.display = "block";
   }

   var elemento = document.getElementById("interface_login");
   if (elemento) {
      elemento.style.display = "block";
   }
}

/**
 * @author Joab Torres Alencar
 * @description para tratamento de preenchimento de campos
 */
$(document).ready(function () {
   mostrarConteudo();
   $(".input-data").mask("99/99/9999");
   $(".input-cpf").mask("999.999.999-99");
   $(".input-hora").mask("99:99:00");
   $('[data-toggle="popover"]').popover({ html: true });
   $('[data-toggle="popover"]').on("click", function () {
      $(this).popover("show");
   });
   //oculta o arlert de mensagem
   $("[data-hide]").on("click", function () {
      $("#alert-msg").toggle().addClass("oculta");
   });
});
$(function () {
   $(".input-calendar").datepicker({
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd/mm/yy",
   });
   $(".input-calendario-usuario").datepicker({
      changeYear: true,
      numberOfMonths: 1,
      dateFormat: "dd/mm/yy",
      minDate: minDate,
      maxDate: maxDate,
   });
   $(".input-calendario-administrador").datepicker({
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd/mm/yy",
   });
   $(".date_serach").datepicker({
      changeYear: true,
      changeMonth: true,
      dateFormat: "dd/mm/yy",
   });
});
/**
 * @author Joab Torres Alencar
 * @description Está função submite o forumlário de buscar rápida que está no menu da direita
 */
function submit_form_navbar() {
   if (document.nSearchSGL) {
      document.nSearchSGL.submit();
   }
}

//home
if (document.getElementById("homePage2")) {
   function select_lab(lab) {
      $(".labs").addClass("hide");
      $(".labs").removeClass("show");
      $(".table").addClass("hide");
      $(".table").removeClass("show");
      setTimeout(function () {
         if (lab != "") {
            $("." + lab).addClass("show");
            $("." + lab).removeClass("hide");
         } else {
            $(".labs").addClass("show");
            $(".labs").removeClass("hide");
         }
         $(".table").addClass("show");
         $(".table").removeClass("hide");
      }, 50);
   }
}

//laboratorio
$(document).ready(function () {
   $(".single-select").select2({
      placeholder: "Selecione",
      width: "100%",
      allowClear: true,
   });
});
function select2() {
   var tag = document.getElementsByClassName("js-example-basic-single");
   if (tag[0]) {
      $(document).ready(function () {
         $(".js-example-basic-single").select2({
            placeholder: "Selecione",
            allowClear: true,
            width: "100%",
         });
      });
   }
}

function FormataStringData(data) {
   var dia = data.split("/")[0];
   var mes = data.split("/")[1];
   var ano = data.split("/")[2];

   return ano + "-" + ("0" + mes).slice(-2) + "-" + ("0" + dia).slice(-2);
   // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
}
//administrador / usuario
if (document.getElementById("container-usuario-form")) {
   ocultaCurso();
   /**
    * @author Joab Torres <joabtorres1508@gmail.com>
    * @description Este codigo abaixo é responsável para fazer o carregamento da imagem setada pelo usuário ao muda a foto do perfil
    */
   readURL = function (input) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         var num = input.name.replace("tImagem-", "");
         reader.onload = function (e) {
            $("#viewImagem-" + num).attr("src", e.target.result);
         };
         reader.readAsDataURL(input.files[0]);
      }
   };
   /**
    * @author Joab Torres <joabtorres1508@gmail.com>
    * @description Este codigo abaixo é responsável para fazer o carregamento da imagem setada pelo usuário ao muda a foto do perfil
    */
   readDefaultURL = function () {
      var valor = $("input[name=nSexo]:checked").val();
      if (valor === "M") {
         $("#viewImagem-1").attr(
            "src",
            base_url + "assets/imagens/user_masculino.png"
         );
      } else {
         $("#viewImagem-1").attr(
            "src",
            base_url + "assets/imagens/user_feminino.png"
         );
      }
      if ($("#imagemStatus").val() === "0") {
         $("#imagemStatus").val("1");
      }
   };

   $(document).ready(function () {
      $("#iCategoria").change(function () {
         ocultaCurso();
      });
   });
   function ocultaCurso() {
      switch ($("#iCategoria").val()) {
         case "Aluno(a)":
            $("#iCurso").removeAttr("disabled");
            $("#viewCurss").addClass("show");
            $("#viewCurss").removeClass("hide");
            break;
         default:
            $("#iCurso").attr("disabled", "disabled");
            $("#viewCurss").addClass("hide");
            $("#viewCurss").removeClass("show");
            break;
      }
   }
}
if (document.getElementById("container-reserva")) {
   $(document).ready(function () {
      consultaHorario();
      validar_data();
      $("#selectUser").change(function () {
         consultaHorario();
      });
      $("#iEquip").change(function () {
         consultaHorario();
      });
      $("#cDataFinal").change(function () {
         validar_data();
      });
      $("#cDataInicial").change(function () {
         validar_data();
      });
   });

   function validar_data() {
      var dateInicial = new Date(FormataStringData($("#cDataInicial").val()));
      if (document.getElementById("cDataFinal")) {
         var dateFinal = new Date(FormataStringData($("#cDataFinal").val()));
      } else {
         var dateFinal = new Date(FormataStringData($("#cDataInicial").val()));
      }

      if (dateFinal.getTime() > dateInicial.getTime()) {
         $("#iDiasdaSemana").addClass("show");
         $("#iDiasdaSemana").removeClass("hide");
         $("#iSegunda").removeAttr("disabled");
         $("#iTerca").removeAttr("disabled");
         $("#iQuarta").removeAttr("disabled");
         $("#iQuinta").removeAttr("disabled");
         $("#iSexta").removeAttr("disabled");
         $("#iSAb").removeAttr("disabled");
      } else {
         $("#iDiasdaSemana").addClass("hide");
         $("#iDiasdaSemana").removeClass("show");
         $("#iSegunda").attr("disabled", "disabled");
         $("#iTerca").attr("disabled", "disabled");
         $("#iQuarta").attr("disabled", "disabled");
         $("#iQuinta").attr("disabled", "disabled");
         $("#iSexta").attr("disabled", "disabled");
         $("#iSAb").attr("disabled", "disabled");
      }
   }
   function consultaHorario() {
      var dados = new Object();
      dados["id_usuario"] = $("#selectUser").val();
      dados["id_lab"] = $("#iEquip").val();
      $.ajax({
         url: base_url + "cadastrar/getHorario",
         type: "POST",
         data: dados,
         dataType: "json",
         success: function (resultados) {
            $("#iHorario").empty();
            if (resultados.length > 0) {
               var qtd = 0;
               for (let resultado of resultados) {
                  switch (resultado.categoria) {
                     case "Aluno(a)":
                        showTurma(true);
                        ocultarSabado();
                        break;
                     case "Professor(a)":
                        showTurma(false);
                        break;
                     default:
                        showTurma(true);
                        break;
                  }
                  ++qtd;
                  nHorario = document.querySelector("#iSetHorario");
                  if (nHorario != "undefined" || nHorario != "null") {
                     var hora = `${resultado.hora_inicial} - ${resultado.hora_final}`;
                     if (nHorario == hora) {
                        $("#iHorario").append(
                           `<option value="${resultado.hora_inicial} - ${resultado.hora_final}" selected="selected">${resultado.hora_inicial} - ${resultado.hora_final}</option>`
                        );
                     } else {
                        $("#iHorario").append(
                           `<option value="${resultado.hora_inicial} - ${resultado.hora_final}">${resultado.hora_inicial} - ${resultado.hora_final}</option>`
                        );
                     }
                  } else {
                     $("#iHorario").append(
                        `<option value="${resultado.hora_inicial} - ${resultado.hora_final}">${resultado.hora_inicial} - ${resultado.hora_final}</option>`
                     );
                  }
               }
               if (qtd > 0) {
                  $("#form-horario").removeClass("has-error");
                  $("#msgHorario").addClass("hide");
                  $("#msgHorario").removeClass("show-inline");
               } else {
                  $("#form-horario").addClass("has-error");
                  $("#msgHorario").addClass("show-inline");
                  $("#msgHorario").removeClass("hide");
               }
            }
         },
      });
   }
   function ocultarSabado() {
      $("#iSAb").attr("disabled", "disabled");
   }
   function showTurma(testeLogico) {
      if (testeLogico == true) {
         $("#iTurma").attr("disabled", "disabled");
         $("#iDisciplina").attr("disabled", "disabled");
         $("#turmasDisc").removeClass("show");
         $("#turmasDisc").addClass("hide");
      } else {
         $("#iTurma").removeAttr("disabled");
         $("#iDisciplina").removeAttr("disabled");
         $("#turmasDisc").removeClass("hide");
         $("#turmasDisc").addClass("show");
      }
   }
}
