<html>
<head>
    <title>Helper</title>
    <meta charset="UTF-8"/>
</head>
<body>
<h2>Request</h2>
<table>
    <tr>
        <td>
            <p>Get users</p>
            <form action="/users/get/" method="get" id="users-get">
                <input type="text" name="user" placeholder="External Id">
                <input type="text" name="email" placeholder="Email">
                <input type="submit" value="Send">
            </form>
        </td>
    </tr>
</table>
<h2>Response</h2>
<pre id="result"></pre>
<script
    src="http://code.jquery.com/jquery-1.12.4.js"
    integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
    crossorigin="anonymous"></script>
<script>
    $('#users-get').on('submit', function(){
        var url = $(this).attr('action') + '?',
            user = $(this).children('input[name="user"]').val(),
            email = $(this).children('input[name="email"]').val();
        if (user) {
            url += 'user=' + user + '&';
        }
        if (email) {
            url += 'email=' + email;
        }
        console.log(url);
       $.get(url , function(r){
           $('#result').text(r);
       })
        return false;
    });
</script>
</body>
</html>