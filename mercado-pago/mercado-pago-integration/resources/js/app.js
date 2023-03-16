import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("ck-method-card").addEventListener("change", function (event) {
    document.getElementById("div-creditcard").style.display = "block";
    document.getElementById("div-billet").style.display = "none";
  });
  document.getElementById("ck-method-billet").addEventListener("change", function (event) {
    document.getElementById("div-creditcard").style.display = "none";
    document.getElementById("div-billet").style.display = "block";
  });
  document.getElementById('btn-payment-submit-billet').addEventListener("click", function (event) {
    billetFormSubmit();
  });
});

// Submit comum boleto
function billetFormSubmit() {
  console.log("Chamou submit boleto");
  const gateway = document.getElementsByName("gateway")[1].value;
  const method = document.getElementsByName("method")[1].value;
  const _token = document.getElementsByName("_token")[1].value;
  const amount = 300;
  const docType = document.getElementById("doc-type").value;
  const docNumber = document.getElementById("doc-number").value;
  const email = document.getElementById("email").value;
  const name = document.getElementById("name").value;

  fetch('payment', {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      transaction_amount: Number(amount),
      payer: {
        email,
        name: name,
        identification: {
          type: docType,
          number: docNumber,
        },
      },
      gateway: gateway,
      method: method,
      _token: _token
    })
  }).then(
    (response) => response.json()
  ).then((data) => {
    console.log(data);
    if (data.errors.length == 0 && data.params.redirect != '') {
      window.location.href = data.params.redirect + '?billet_url=' + data.params.billet_url;
    }
  }).catch((error) => {
    // get payment result error
    console.log('aqui', error);
  });
};

// Lib SDK Mercado Pago tokenização cartão
const mp = new MercadoPago('TEST-2da1a4b3-6ce6-4044-b610-00718add9291', {
  locale: 'en-US'
})
const cardForm = mp.cardForm({
  amount: "100.5",
  iframe: true,
  form: {
    id: "form-checkout",
    cardNumber: {
      id: "form-checkout__cardNumber",
      placeholder: "Card number",
    },
    expirationDate: {
      id: "form-checkout__expirationDate",
      placeholder: "MM/YYYY",
    },
    securityCode: {
      id: "form-checkout__securityCode",
      placeholder: "CVV",
    },
    cardholderName: {
      id: "form-checkout__cardholderName",
      placeholder: "Cardholder name",
    },
    issuer: {
      id: "form-checkout__issuer",
      placeholder: "Issuer",
    },
    installments: {
      id: "form-checkout__installments",
      placeholder: "Total installments",
    },
    identificationType: {
      id: "form-checkout__identificationType",
      placeholder: "Document type",
    },
    identificationNumber: {
      id: "form-checkout__identificationNumber",
      placeholder: "Document number",
    },
    cardholderEmail: {
      id: "form-checkout__cardholderEmail",
      placeholder: "Email",
    },
  },
  callbacks: {
    onFormMounted: error => {
      if (error) return console.warn("Form Mounted handling error: ", error);
      console.log("Form mounted");
    },
    onSubmit: event => {
      event.preventDefault();

      const {
        paymentMethodId: payment_method_id,
        issuerId: issuer_id,
        cardholderEmail: email,
        amount,
        token,
        installments,
        identificationNumber,
        identificationType
      } = cardForm.getCardFormData();

      const gateway = document.getElementsByName("gateway")[0].value;
      const method = document.getElementsByName("method")[0].value;
      const _token = document.getElementsByName("_token")[0].value;

      fetch("/payment", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          token,
          issuer_id,
          payment_method_id,
          transaction_amount: Number(amount),
          installments: Number(installments),
          description: "Product description",
          payer: {
            email,
            identification: {
              type: identificationType,
              number: identificationNumber,
            },
          },
          gateway: gateway,
          method: method,
          _token: _token
        })
      }).then(
        (response) => response.json()
      ).then((data) => {
        console.log(data);
        if (data.errors.length == 0 && data.params.redirect != '') {
          window.location.href = data.params.redirect;
        }
      }).catch((error) => {
        // get payment result error
        console.log('aqui', error);
      });
    },
    onError: (error) => {
      // callback called for all Brick error cases
      console.error(error);
    },
    onFetching: (resource) => {
      console.log("Fetching resource: ", resource);

      // Animate progress bar
      const progressBar = document.querySelector(".progress-bar");
      progressBar.removeAttribute("value");

      return () => {
        progressBar.setAttribute("value", "0");
      };
    }
  },
});

