[![Stand With Palestine](https://raw.githubusercontent.com/TheBSD/StandWithPalestine/main/banner-no-action.svg)](https://TheBSD.github.io/StandWithPalestine/)

![banner](https://banners.beyondco.de/KuraimiBank%20Payment%20Gateway.png?theme=light&packageManager=composer+require&packageName=al-saloul%2Fkuraimibank-payment&pattern=bankNote&style=style_1&description=A+library+designed+to+facilitate+transactions+between+e-commerces+and+the+Kuraimi+bank+applications.&md=1&showWatermark=1&fontSize=75px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg&widths=250&heights=250)

<img src="https://kuraimibank.com//media/3578/%D8%AD%D8%A7%D8%B3%D8%A8.png" width="150" height="150" />

# KuraimiBank Payment Gateway

The KuraimiBank-Payment Gateway is a comprehensive library designed to facilitate seamless transactions between domestic e-commerce suppliers and the Kuraimi bank applications, namely KJ and Mfloos. This API solution serves as a bridge, connecting the supplier's portal with the bank's applications, enabling efficient and secure payment processing.

By leveraging the KuraimiBank-Payment Gateway, suppliers can achieve enhanced integration with their front systems and platforms through the utilization of Web API technology. This integration empowers suppliers and their partners to conduct transactions effortlessly, ensuring a smooth user experience for their customers.

With its robust features and functionalities, the KuraimiBank-Payment Gateway simplifies the payment process for e-commerce suppliers. It enables them to securely manage and process payments, ensuring reliable and efficient financial transactions.

Overall, the KuraimiBank-Payment Gateway serves as a vital component in Kuraimi's E-Pay API solution, offering domestic e-commerce suppliers a powerful tool to seamlessly integrate their systems with Kuraimi bank applications, ultimately enhancing their payment processing capabilities.

## Installation

You can install the package via Composer:

```bash
composer require al-saloul/kuraimibank-payment
```

## Usage

Create these `.env` variables in your project:

```env
KURAIMI_USERNAME=
KURAIMI_PASSWORD=
KURAIMI_BASE_URL=
KURAIMI_CURRENCY_ZONE=old # Ex: all, old, new
KURAIMI_WEBHOOK_URL=webhooks/kuraimibank/check-user # You can add your own webhook url here which should not exist in your project routes
KURAIMI_MODEL='\App\Models\User' # The model with namespace
KURAIMI_COLUMN_NAME='mobile'
```

The currency zone variable represent North Yemen Coin (old) , South Yemen Coin (new) or all Yemen coins (all).

