/* eslint-disable no-undef */
/**
 * API communication
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 August 2020
 */
"use strict";

$(document).ready(function() {

    // Search movie
    $("#frmSearchFilm").on("submit", function(e) {
        e.preventDefault();

        loadingStart();

        $.ajax({
            url: "src/api.php",
            type: "POST",
            data: {
                entity: "movie",
                action: "search",
                searchText: $("#txtFilm").val()
            },
            success: function(data) {
                displayMovies(JSON.parse(data));
                loadingEnd();
            }
        });
    });

    // Show movie
    $(document).on("click", "img.showMovie, img.editMovie", function() {
        const action = ($(this)[0].className === "smallButton showMovie" ? 'show' : 'edit');
        const id = $(this).attr("data-id");

        $.ajax({
            url: "src/api.php",
            type: "POST",
            data: {
                entity: "movie",
                action: "get",
                id: id
            },
            success: function(data) {
                const movieInfo = JSON.parse(data);

                $("#txtTitle").val(movieInfo["title"]);
                $("#txtOverview").val(movieInfo["overview"]);
                $("#txtReleaseDate").val(movieInfo["release_date"]);
                $("#txtRuntime").val(movieInfo["runtime"]);

                const lstDirectors = $("<select />");
                for (const director of movieInfo["directors"]) {
                    lstDirectors.append($("<option />", { "value": director["person_id"], "text": director["person_name"] }))
                }
                $("#lstDirector").html(lstDirectors.html());

                const lstActors = $("<select />");
                for (const actor of movieInfo["actors"]) {
                    lstActors.append($("<option />", { "value": actor["person_id"], "text": actor["person_name"] }))
                }
                $("#lstActor").html(lstActors.html());

                showMovieModal(action, id);
            }
        });
    });

    // Add/update movie
    $("button#btnFilmOk").on("click", function() {

        const action = ($(this).text() === "Add film" ? "add" : "update");

        let directors = [];
        let actors = [];

        $("#lstDirector > option").each(function() { 
            directors.push($(this).val()); 
        })
        $("#lstActor > option").each(function() { 
            actors.push($(this).val()); 
        })

        let info = {
            "title": $("#txtTitle").val(),
            "overview": $("#txtOverview").val(),
            "releaseDate": $("#txtReleaseDate").val(),
            "runtime": $("#txtRuntime").val(),
            "directors": directors,
            "actors": actors
        }
        if (action === "update") {
            info["movieId"] = $("div#modalFilm").attr("data-id");
        }

        $.ajax({
            url: "src/api.php",
            type: "POST",
            data: {
                entity: "movie",
                action: action,
                info: info
            },
            success: function(data) {
                // Info is requeried, just in case the searched data has changed
                if (action === "update") {
                    $("#frmSearchFilm").submit();   
                }
                hideModal("film");
                showMessage("movie", action, (parseInt(JSON.parse(data)) !== -1));
            }
        });
    });    

    // Delete movie
    $(document).on("click", "img.deleteMovie", function() {
        const id = $(this).attr("data-id");

        if (confirm("Are you sure that you want to delete this film?")) {

            $.ajax({
                url: "src/api.php",
                type: "POST",
                data: {
                    entity: "movie",
                    action: "delete",
                    id: id
                },
                success: function(data) {
                    $("img[data-id=" + id + "]").parent().parent().remove();     // The table row is removed

                    showMessage("movie", "delete", (parseInt(JSON.parse(data)) !== -1));                    
                }
            });
        }
    });

    // Search person
    $("#frmSearchPerson").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "src/api.php",
            type: "POST",
            data: {
                entity: "person",
                action: "search",
                searchText: $("#txtPerson").val()
            },
            success: function(data) {
                displayPersons(JSON.parse(data));
            }
        });
    });

    // Add a new person
    $("img#btnAddPerson").on("click", function() {
        const personType = $("#headerPerson").text().substring(4).trim();
        const person = prompt("Name of the new " + personType);

        if (person !== null) {
            $.ajax({
                url: "src/api.php",
                type: "POST",
                data: {
                    entity: "person",
                    action: "add",
                    personName: person
                },
                success: function(data) {
                    const personID = parseInt(JSON.parse(data));

                    if (personID === -1) {
                        alert("The " + personType + " already exists");
                    } else {
                        const lstPerson = $("#lstPerson");

                        lstPerson.empty();
                        lstPerson.append($("<option />", { "value": personID, "text": person}));                    
                    }
                }
            });
        }
    });

    // Delete a person
    $("img#btnDeletePerson").on("click", function() {
        const personType = $("#headerPerson").text().substring(4).trim();
        const personID = $("#lstPerson > option:selected").val();

        if (personID === undefined) {
            alert("Please select a " + personType + " to delete");
            return;
        } 

        if (confirm("Are you sure that you want to delete this " + personType + "?")) {
            $.ajax({
                url: "src/api.php",
                type: "POST",
                data: {
                    entity: "person",
                    action: "delete",
                    personID: personID
                },
                success: function(data) {
                    if (parseInt(JSON.parse(data)) === -1) {
                        alert("The " + personType + " cannot be deleted, since s/he is associated to films. Please deassign him/her from those films first, then try deleting him/her again");
                    } else {
                        alert("The " + personType + " was successfully deleted");
                        $("#lstPerson > option:selected").remove();
                    }
                }
            });
        }
    });
});