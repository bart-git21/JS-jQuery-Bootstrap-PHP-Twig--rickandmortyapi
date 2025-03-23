<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Application for testing GraphQL queries</h1>
        <h3 class="text-center">(with appreciation to rickandmortyapi.com)</h3>
        <div class="row gx-5">
            <div class="col border">
                <form style="max-width:300px;">
                    <h4 class="text-center">Choose the values</h4>
                    <?php include "components/select.php" ?>
                    <?php include "components/input.php" ?>
                    <button id="getDataBtn" type="submit" class="btn btn-primary">Get</button>
                </form>
            </div>
            <div class="col border">
                <h4 class="text-center">Result</h4>
                <div id="output"></div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            class Card {
                constructor(response) {
                    this.list = response.data.characters.results;
                }
                addCard(data) {
                    return `
                    <div class="card" style="width: 18rem;">
                        <img src="${data.image}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">${data.name}</h5>
                            <p class="card-text">${data.species} - ${data.status}</p>
                        </div>
                    </div>
                    `
                }
                display() {
                    this.list.forEach(
                        e => $("#output").append(this.addCard(e))
                    )
                }
            }
        const isValidInputValue = (value) => (/^[\d,]*$/.test(value));
        const query = {
            nameSelect: "",
            statusSelect: "",
            genderSelect: "",
            speciesSelect: "",
            locationInput: "",
            episodeInput: "",
        }
        let errors = false;

        $('#getDataBtn').on('click', function (event) {
            event.preventDefault();
            errors = false;
            $("small#errorMsg").each(function () {
                this.textContent = "";
            })
            $('select').each(function () {
                query[$(this)[0].id] = $(this).val();
            })
            $('input').each(function () {
                if (isValidInputValue(this.value)) {
                    query[this.id] = this.value;
                } else {
                    errors = true;
                    $(this).next().text("Invalid data. Please enter numbers only, separated by comma.");
                }
            })
            !errors && $.ajax({
                url: `/api/`,
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                data: JSON.stringify(query),
            })
                .done((response) => {
                    $('select').each(function () {
                        $(this).val("");
                    })
                    $('input').each(function () {
                        this.value = "";
                    })
                    console.log(response);
                    const cards = new Card(response);
                    cards.display();
                })
                .fail((xhr, status, error) => {
                    console.log(error);
                    console.log(xhr.status);
                });
        })
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>