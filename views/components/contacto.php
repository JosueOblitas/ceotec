<section class="wrapper bg-light">
    <div class="container py-14 py-md-16">
        <h2 class="fs-32 text-uppercase text-red mb-1" style="position: relative;vertical-align: top;">
            Hablemos de tu requerimiento</h2>
        <p style="font-size: 20px;position: relative;vertical-align: top;color: #000;" class="pb-4">
            Nuestro equipo de ventas estará atento a tu mensaje</p>
        <div class="row gy-10 gx-lg-8 gx-xl-12 align-items-start">
            <!--/column -->
            <div class="col-lg-7 position-relative">
                <img width="400px" class="d-flex mx-auto mb-5"
                    src="https://res.cloudinary.com/dwipjtlpj/image/upload/v1694639335/Contactanos_1_biblhg.png"
                    alt="">
                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <img width="250" class="d-flex mx-auto"
                            src="https://res.cloudinary.com/dwipjtlpj/image/upload/v1694443056/setecloud/Ima07_CEOTEC_ocljkq.png"
                            alt="">
                    </div>
                    <div class="col-md-6 mt-4">
                        <img width="250" src="https://clientes.hostinglabs.net/assets/img/logo.png"
                            class="d-flex mx-auto" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="alert alert-icon" role="alert" id="messageAlert" style="display: none;">
                    <span class="messageIcon"></span><span id="message"></span>
                </div>
                <form class="contact-form" id="contacto">
                    <div class="row gx-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input id="form_name" type="text" name="nombre" class="form-control" placeholder="Jane">
                                <label for="form_name">Nombre *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input id="form_lastname" type="text" name="apellido" class="form-control"
                                    placeholder="Doe">
                                <label for="form_lastname">Apellido *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input id="form_email" type="email" name="correo" class="form-control"
                                    placeholder="jane.doe@example.com">
                                <label for="form_email">Correo *</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input id="phone" type="text" pattern="[789][0-9]{9} name="phone" class="form-control"
                                    placeholder="jane.doe@example.com">
                                <label for="phone">Telefono *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-12">
                            <div class="form-floating mb-4">
                                <textarea id="form_message" name="mensaje" class="form-control"
                                    placeholder="Your message" style="height: 150px"></textarea>
                                <label for="form_message">Mensaje *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-12">
                            <input type="submit" class="btn btn-primary rounded-pill btn-send mb-3"
                                value="Enviar Mensaje">
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <p class=""><strong>
                                    <i class="fa-solid fa-phone"></i> (01)
                                    304 2190
                                    </strong>
                                </p>
                                <p class=""><strong>
                                    <i class="fa-solid fa-envelope"></i> ventas@ceotec.pe
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <!-- /column -->
                    </div>
                    <!-- /.row -->
                </form>
            </div>

            <!--/column -->
        </div>
        <!--/.row -->
    </div>
    <!-- /.container -->
</section>
<!-- /section -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('contacto');
        const messageAlert = document.getElementById('messageAlert');
        const messageIcon = document.querySelector('.messageIcon');
        const messageText = document.getElementById('message');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            try {
                const response = await fetch('https://ceotec.pe/api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const responseData = await response.json();

                console.log('Respuesta del servidor:', responseData);

                messageIcon.innerHTML = responseData.icon;
                messageText.textContent = responseData.message;

                messageAlert.className = 'alert alert-icon';
                messageAlert.classList.add('alert-' + responseData.type.trim());

                messageAlert.style.display = 'block';
            } catch (error) {
                console.error('Error al enviar la solicitud:', error);
            }
        });
    });
</script>