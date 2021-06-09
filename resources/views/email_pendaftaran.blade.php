<div id="email-wrapper" style="font-family:-apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;width:550px;max-width:100% !important;border-width:1px;border-style:solid;border-color:#ddd;box-shadow:5px 5px 0 #ddd;margin-top:auto;margin-bottom:auto;margin-right:auto;margin-left:auto;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;font-size:16px;line-height:28px;box-sizing:border-box;" >
    <header class="header" style="width:100% !important;max-width:100% !important;box-sizing:border-box;" >
        <div class="flex bg-main" style="background-color:#F3F4F8;padding-top:15px;padding-bottom:15px;padding-right:20px;padding-left:20px;border-radius:15px;display:flex;justify-content:space-between;" >
            <img src="https://pandalas.id/assets/wesclic/logo-wesclic.png" alt="Logo Wesclic" class="email-brand" style="width:200px;max-width:100%;object-fit:contain;" >
            <!--<a title="ke Partner Area" href="https://sales.wesclic.com" target="_blank"><img src="https://pandalas.id/assets/wesclic/icon-door-open.png"-->
            <!--        alt="Login" class="icon" style="width:40px;object-fit:contain;" ></a>-->
        </div>
        <h1 style="font-size:20px;display:none;" >Aktivasi Akun</h1>
    </header>
    <main class="email-content bg-main" style="width:100% !important;max-width:100% !important;box-sizing:border-box;background-color:#F3F4F8;padding-top:15px;padding-bottom:15px;padding-right:20px;padding-left:20px;border-radius:15px; margin: 15px 0;" >
        <h2 style="font-size:20px;margin-bottom:10px;margin-top:10px;" >Aktivasi Akun</h2>
        <p>Halo, <strong>{{$nama}}!</strong>
            <br> Terimakasih telah mendaftar di Wesclic Sales. <br>
            Anda dapat mengakses halaman <u>partner area</u> di <a title="ke Partner Area" class="a-link"
                href="https://sales.wesclic.com/" target="_blank" style="color:rgb(0, 140, 255);" >https://sales.wesclic.com</a>.
        </p>

        <div class="email-info" style="border-width:1px;border-style:solid;border-color:#FBD230;background-color:rgb(255, 243, 196);padding-top:5px;padding-bottom:5px;padding-right:20px;padding-left:20px;border-radius:10px;font-size:14px;line-height:24px;" >
            <p>Berikut informasi akun anda:</p>

            Nama Lengkap : {{$nama}}</br>
            Email : <strong>{{$email}}</strong></br>
            No. Handphone : {{$notelp}}</br>
            Password : <strong>{{$password}}</strong></br>
        </div>

        <p>Sebelum Anda dapat mengakses halaman <u>partner area</u>, silakan klik tombol dibawah ini untuk melakukan
            aktivasi akun Anda.</p>
        <div style="text-align:center;" >
            <a class="email-button" href="{{$url}}?email={{$email}}&token={{$kode_aktif}}" target="_blank" style="color:#fff;padding-top:10px;padding-bottom:10px;padding-right:30px;padding-left:30px;background-color:#333;text-decoration:none;border-radius:50px;font-weight:500;font-size:15px;display:inline-block;margin-top:10px;margin-bottom:20px;transition:0.3s ease-in-out;" >Aktivasi Sekarang</a>
            <div style="font-size:11px;line-height:16px;" >
                <small>atau anda juga bisa klik link berikut ini:
                    <a title="Link Aktivasi" class="a-link" href="{{$url}}?email={{$email}}&token={{$kode_aktif}}"
                        target="_blank" style="color:rgb(0, 140, 255);" >{{$url}}?email={{$email}}&token={{$kode_aktif}}</a>
                </small>
            </div>
        </div>
        <p style="margin-bottom:0;" >Terimakasih telah menggunakan layanan Wesclic Sales.</p>
        <p style="margin-bottom:5px;" >Jabat erat,</p>
        <h3 style="margin-top:5px;" >Tim Wesclic</h3>
    </main>
    <footer class="footer bg-main" style="width:100% !important;max-width:100% !important;box-sizing:border-box;background-color:#F3F4F8;padding-top:15px;padding-bottom:15px;padding-right:20px;padding-left:20px;border-radius:15px;" >
        <div class="footer-content flex" style="display:flex;justify-content:space-between;align-items:center;width:90%;margin-top:auto;margin-bottom:auto;margin-right:auto;margin-left:auto;" >
            <ul class="fc-l nav" style="padding-left:0;list-style-type:none;list-style-position:outside;list-style-image:none;" >
                <li style="font-size:15px;" ><a href="" title="Tentang Kami" style="color:#438CEB;text-decoration:none;display:inline-block;padding-top:2px;padding-bottom:2px;padding-right:0;padding-left:0;" >Tentang</a></li>
                <li style="font-size:15px;" ><a href="" title="Pusat" style="color:#438CEB;text-decoration:none;display:inline-block;padding-top:2px;padding-bottom:2px;padding-right:0;padding-left:0;" >Pusat Bantuan</a></li>
                <li style="font-size:15px;" ><a href="" title="Panduan Penggunaan" style="color:#438CEB;text-decoration:none;display:inline-block;padding-top:2px;padding-bottom:2px;padding-right:0;padding-left:0;" >Panduan</a></li>
            </ul>
            <div class="fc-r social">
                <p style="color:#313D2E;font-size:14px;font-weight:400;line-height:23px;" >Selimbi, Jl. Sonopakis Lor, Sonosewu, <br>
                    Ngestiharjo, Kec. Kasihan, Bantul, DIY <br> 55182</p>
                <ul class="social-link" style="padding-left:0;list-style-type:none;list-style-position:outside;list-style-image:none;" >
                    <li style="display:inline-block;" ><a target="_blank" href="https://facebook.com/wesclic" title="Facebook page"
                            class="icon fb" style="background-image: url('https://pandalas.id/assets/wesclic/icon-fb.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a>
                    </li>
                    <li style="display:inline-block;" ><a target="_blank" href="https://instagram.com/wesclic" title="Instagram"
                            class="icon ig" style="background-image: url('https://pandalas.id/assets/wesclic/icon-ig.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a></li>
                    <li style="display:inline-block;" ><a target="_blank" href="https://linkedin.com/in/wesclic" title="Linkedin"
                            class="icon linkd" style="background-image: url('https://pandalas.id/assets/wesclic/icon-linkd.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a></li>
                    <li style="display:inline-block;" ><a target="_blank" href="https://twitter.com/wesclicofficial" title="Twitter"
                            class="icon twt" style="border-radius: 2px;background-image: url('https://pandalas.id/assets/wesclic/icon-twt.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a></li>
                    <li style="display:inline-block;" ><a target="_blank" href="https://www.youtube.com/channel/UC3XFAbcYF5R1VhJ7bdcc5Zg"
                            title="Youtube" class="icon yt" style="background-image: url('https://pandalas.id/assets/wesclic/icon-yt.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a></li>
                    <li style="display:inline-block;" ><a target="_blank" href="https://medium.com/@wesclic" title="Medium"
                            class="icon medium" style="border-radius: 2px;background-image: url('https://pandalas.id/assets/wesclic/icon-medium.png');color:#438CEB;text-decoration:none;display:inline-block;background-position:center center;background-size:100%;background-repeat:no-repeat;width:30px;height:30px;margin-right:5px;" >&nbsp;</a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>

<!-- selesai -->
