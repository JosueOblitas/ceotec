<section class="wrapper bg-light">
    <div class="container py-14 py-md-16">
        <h1 class="display-3 mb-3">Ponte en contacto con nosotros</h1>
        <h2 class="fs-16 text-uppercase text-line text-primary mb-3 pb-10">Nuestro equipo de ventas estarán atentos a tu
            mensaje
        </h2>
        <div class="row gy-10 gx-lg-8 gx-xl-12 align-items-center">
            <!--/column -->
            <div class="col-lg-7 position-relative">
                <div class="shape bg-dot primary rellax w-18 h-18" data-rellax-speed="1"
                    style="top: 0; left: -1.4rem; z-index: 0;"></div>
                <div class="row gx-md-5 gy-5">
                    <div class="col-md-6">
                        <figure class="rounded mt-md-10 position-relative"><img
                                src="https://res.cloudinary.com/dwipjtlpj/image/upload/v1691812356/setecloud/g5_olee53.jpg"
                                srcset="https://res.cloudinary.com/dwipjtlpj/image/upload/v1691812356/setecloud/g5_olee53.jpg"
                                alt=""></figure>
                    </div>
                    <!--/column -->
                    <div class="col-md-6">
                        <div class="row gx-md-5 gy-5">
                            <div class="col-md-12 order-md-2">
                                <figure class="rounded"><img
                                        src="https://res.cloudinary.com/dwipjtlpj/image/upload/v1691812397/setecloud/g6_it3wjf.jpg"
                                        srcset="https://res.cloudinary.com/dwipjtlpj/image/upload/v1691812397/setecloud/g6_it3wjf.jpg"
                                        alt=""></figure>
                            </div>
                            <!--/column -->
                            <div class="col-md-10">
                                <div class="card bg-pale-primary text-center counter-wrapper">
                                    <div class="card-body py-11">
                                        <h3 class="counter text-nowrap">3500+</h3>
                                        <p class="mb-0">Clientes satisfechos</p>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->
                        </div>
                        <!--/.row -->
                    </div>
                    <!--/column -->
                </div>
                <!--/.row -->
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
                                <label for="form_name">First Name *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input id="form_lastname" type="text" name="apellido" class="form-control"
                                    placeholder="Doe">
                                <label for="form_lastname">Last Name *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input id="form_email" type="email" name="correo" class="form-control"
                                    placeholder="jane.doe@example.com">
                                <label for="form_email">Email *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-12">
                            <div class="form-floating mb-4">
                                <textarea id="form_message" name="mensaje" class="form-control"
                                    placeholder="Your message" style="height: 150px"></textarea>
                                <label for="form_message">Message *</label>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-12">
                            <input type="submit" class="btn btn-primary rounded-pill btn-send mb-3"
                                value="Enviar Mensaje">
                            <p class="text-muted"><strong>*</strong> Todos los campos son requeridos</p>
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