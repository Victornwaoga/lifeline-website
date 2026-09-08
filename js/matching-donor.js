document.addEventListener("DOMContentLoaded", function () {

    const contactButtons = document.querySelectorAll(".contact-button");

    contactButtons.forEach(function (button) {

        button.addEventListener("click", async function () {

            const donorId = this.dataset.donorId;

            if (!donorId) {
                alert("Donor information is unavailable.");
                return;
            }

            this.disabled = true;
            this.textContent = "Loading...";

            try {

                const formData = new FormData();
                formData.append("donor_id", donorId);

                const response = await fetch("php/get-donor-contact.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (data.success) {

                    alert(
                        "Donor: " + data.donor.full_name +
                        "\nBlood Type: " + data.donor.blood_type +
                        "\nLocation: " + data.donor.location +
                        "\nPhone: " + data.donor.phone
                    );

                } else {

                    alert(data.message || "Unable to retrieve donor contact.");

                }

            } catch (error) {

                console.error("Contact error:", error);
                alert("Something went wrong while retrieving the donor contact.");

            } finally {

                this.disabled = false;
                this.innerHTML = 'View Contact <span>→</span>';

            }

        });

    });

});