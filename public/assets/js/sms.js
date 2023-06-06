

function countCharacters()
{
    let messageField = document.querySelector("#message")
    messageField.addEventListener('keyup',function(){
        const pages_counts = document.querySelector("#pages_counts")
        const character_counts = document.querySelector("#character_counts")
        const showTotalCharactersObject = showTotalCharacters()
        const pages = showTotalCharactersObject.total_pages
        pages_counts.textContent=pages
        character_counts.textContent = showTotalCharactersObject.textCount
    })
}

function showTotalCharacters()
{
    let messageField = document.querySelector("#message")
    messageFieldValue = messageField.value
    const charactersTypedCount = messageFieldValue.length
    const firstPage = messageFieldValue.slice(0,160)
    let remainingPages =0

    if(firstPage.length === 160 && charactersTypedCount > 160){
        const remainingtexts = messageFieldValue.length - firstPage.length
        remainingPages = Math.ceil(remainingtexts /154)
    }
    return {total_pages:remainingPages + 1,textCount:charactersTypedCount}
}

function sendSMS()
{
    let send_sms_btn = $("#send_sms_btn")
    let sender = $("#sender")
    let phone = $("#phone")
    let message = $("#message")
    send_sms_btn.click(function(e){
        e.preventDefault()
        let error = false

        if(sender.val().length < 3 ){
         errorResponse="Sender Id field must not be less than 3 characters"
            error = true
        }else if(phone.val().length < 11 ){
            error = true
         errorResponse="Phone field must contain at least one valid phone number"
        }else if(message.val().length < 1){
            error =true
         errorResponse="The message field can not be empt"
        }
        if(!error){
            
            let data= { phone_number:phone.val(),sender:sender.val(),message:message.val() }  // data to submit
            
            $.ajax({
                type: "POST",
                url: "/send",
                data: data,
                dataType: "json",
                encode: true,
              }).done(function (data) {
                  alert(
                    `pageCount:  ${data.pageCount},
                    receivers :  ${data.receivers.join(",")},
                    cost:${data.smsCost}`
                  )
              }).error(function(er){
                console.log(er)
              });
        }else{
            $("#display_error_message").html(errorResponse)
            
        }
    })
}


countCharacters()
sendSMS()