$(function() {
    // hide state input if not us, australia or canada
    $( '#country').change(function() {
        if(document.getElementById('country').value !== 'US'
            && document.getElementById('country').value !== 'AU'
            && document.getElementById('country').value !== 'CA'
            && document.getElementById('country').value !== ''){
            $('#state').prev('label').hide();
            $('#state').hide().value = '';
            $('#state-error').hide().value = '';
        }
        else {
            $('#state').show();
            $('#state').prev('label').show();
        }
    });

    // stripePublicKey var defined in index page
    var stripe = Stripe('live_key');
    var elements = stripe.elements();

    // custom styling
    var cardOptions = {
        style: {
            base: {
                color: '#32325d',
                lineHeight: '24px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#FF0000',
                iconColor: '#fa755a'
            }
        }
    };

    // create an instance of the card Element
    var card = elements.create('cardNumber', cardOptions);
    var expiry = elements.create('cardExpiry', cardOptions);
    var cvc = elements.create('cardCvc', cardOptions);

    card.mount('#credit-card');
    expiry.mount('#card-expiry');
    cvc.mount('#card-cvc');

    // handle real-time change
    card.addEventListener('change', changeHandler(document.querySelector('#card-valid')));
    expiry.addEventListener('change', changeHandler(document.querySelector('#card-expiry-valid')));
    cvc.addEventListener('change', changeHandler(document.querySelector('#card-cvc-valid')));


    function dummyFunction(validationInput) {
        return function (event) {
            // validation errors from the card element
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                validationInput.value = 0;
                displayError.textContent = event.error.message;
                displayError.hidden = false;
            } else {
                validationInput.value = 1;
                displayError.textContent = '';
                displayError.hidden = true;

                $(validationInput).valid();
            }
        }
    }

    function foo() {
        alert('hello');
        console.log('hello');
    }

    function bar(a, b, c) {
        a++;
        b+=c;
        var d=a+b;
        var e = d + c;
        alert(e);
    }
});
