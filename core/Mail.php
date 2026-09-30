<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mail
{
    public static function send($toEmail, $toName, $subject, $body)
    {
        $config = require __DIR__ . '/../config/smtp.php';

        $mail = new PHPMailer(true);

        try {

            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['port'];

            // Sender
            $mail->setFrom(
                $config['from_email'],
                $config['from_name']
            );

            // Receiver
            $mail->addAddress($toEmail, $toName);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Send
            $mail->send();

            return true;

        } catch (Exception $e) {

            error_log('Email could not be sent: ' . $mail->ErrorInfo);

            return false;
        }
    }

    public static function sendOrderConfirmation(
        $toEmail,
        $toName,
        $orderNumber,
        $total
    ) {
        $subject = "Order Confirmation - " . $orderNumber;

        $body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Order Confirmation</title>
    </head>

    <body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:30px;">

        <div style="max-width:600px; margin:auto; background:white; padding:30px;">

            <h2 style="margin-bottom:20px;">
                Thank you for your order!
            </h2>

            <p>Hello ' . htmlspecialchars($toName) . ',</p>

            <p>
                Your order has been successfully placed with
                <strong>ElectroCart</strong>.
            </p>

            <p>
                <strong>Order Number:</strong>
                ' . htmlspecialchars($orderNumber) . '
            </p>

            <p>
                <strong>Total Amount:</strong>
                PKR ' . number_format($total, 2) . '
            </p>

            <p>
                Your order is currently being processed.
            </p>

            <p>
                We will notify you by email whenever your
                order status changes.
            </p>

            <p>
                Thank you for shopping with ElectroCart.
            </p>

        </div>

    </body>
    </html>
    ';

        return self::send(
            $toEmail,
            $toName,
            $subject,
            $body
        );
    }

    public static function sendOrderStatusUpdate(
        $toEmail,
        $toName,
        $orderNumber,
        $total,
        $orderStatus
    ) {
        $statusText = ucfirst($orderStatus);

        switch ($orderStatus) {

            case 'processing':
                $subject = "Your Order is Being Processed - " . $orderNumber;

                $heading = "Your Order is Being Processed";
                $message = "Good news! We have received your order and our team is now preparing it for shipment.";

                break;

            case 'shipped':
                $subject = "Your Order Has Been Shipped - " . $orderNumber;

                $heading = "Your Order Has Been Shipped";
                $message = "Great news! Your order has been shipped and is now on its way to you.";

                break;

            case 'delivered':
                $subject = "Your Order Has Been Delivered - " . $orderNumber;

                $heading = "Your Order Has Been Delivered";
                $message = "Your order has been successfully delivered. We hope you enjoy your purchase!";

                break;

            case 'cancelled':
                $subject = "Your Order Has Been Cancelled - " . $orderNumber;

                $heading = "Your Order Has Been Cancelled";
                $message = "Your order has been cancelled. If you did not request this cancellation or have any questions, please contact our support team.";

                break;

            default:
                $subject = "Order Status Update - " . $orderNumber;

                $heading = "Your Order Status Has Been Updated";
                $message = "Your order status has been updated.";
        }

        $body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($heading) . '</title>
    </head>

    <body style="
        margin:0;
        padding:0;
        background-color:#f4f6f8;
        font-family:Arial, Helvetica, sans-serif;
    ">

        <div style="
            width:100%;
            padding:40px 15px;
            box-sizing:border-box;
        ">

            <div style="
                max-width:600px;
                margin:0 auto;
                background:#ffffff;
                border-radius:10px;
                overflow:hidden;
                box-shadow:0 3px 15px rgba(0,0,0,0.08);
            ">

                <!-- Header -->

                <div style="
                    background:#111827;
                    padding:25px;
                    text-align:center;
                ">

                    <h1 style="
                        margin:0;
                        color:#ffffff;
                        font-size:28px;
                        letter-spacing:1px;
                    ">
                        ElectroCart
                    </h1>

                    <p style="
                        margin:8px 0 0;
                        color:#d1d5db;
                        font-size:14px;
                    ">
                        Electronics at Your Fingertips
                    </p>

                </div>


                <!-- Content -->

                <div style="
                    padding:35px 30px;
                ">

                    <h2 style="
                        margin:0 0 20px;
                        color:#111827;
                        font-size:24px;
                    ">
                        ' . htmlspecialchars($heading) . '
                    </h2>


                    <p style="
                        margin:0 0 15px;
                        color:#374151;
                        font-size:16px;
                        line-height:1.6;
                    ">
                        Hello <strong>' . htmlspecialchars($toName) . '</strong>,
                    </p>


                    <p style="
                        margin:0 0 25px;
                        color:#4b5563;
                        font-size:15px;
                        line-height:1.7;
                    ">
                        ' . htmlspecialchars($message) . '
                    </p>


                    <!-- Order Information -->

                    <div style="
                        background:#f9fafb;
                        border:1px solid #e5e7eb;
                        border-radius:8px;
                        padding:20px;
                        margin-bottom:25px;
                    ">

                        <h3 style="
                            margin:0 0 15px;
                            color:#111827;
                            font-size:17px;
                        ">
                            Order Information
                        </h3>


                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">
                            <strong>Order Number:</strong>
                            ' . htmlspecialchars($orderNumber) . '
                        </p>


                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">
                            <strong>Order Total:</strong>
                            PKR ' . number_format($total, 2) . '
                        </p>


                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">
                            <strong>Current Status:</strong>
                            ' . htmlspecialchars($statusText) . '
                        </p>

                    </div>


                    <p style="
                        margin:0;
                        color:#4b5563;
                        font-size:14px;
                        line-height:1.6;
                    ">
                        Thank you for shopping with <strong>ElectroCart</strong>.
                    </p>

                </div>


                <!-- Footer -->

                <div style="
                    background:#f9fafb;
                    border-top:1px solid #e5e7eb;
                    padding:20px;
                    text-align:center;
                ">

                    <p style="
                        margin:0;
                        color:#6b7280;
                        font-size:13px;
                    ">
                        This is an automated email from ElectroCart.
                    </p>

                    <p style="
                        margin:8px 0 0;
                        color:#9ca3af;
                        font-size:12px;
                    ">
                        Please do not reply directly to this email.
                    </p>

                </div>

            </div>

        </div>

    </body>
    </html>
    ';

        return self::send(
            $toEmail,
            $toName,
            $subject,
            $body
        );
    }
    public static function sendCancellationEmail(
        $toEmail,
        $toName,
        $orderNumber,
        $total,
        $paymentMethod,
        $paymentStatus
    ) {
        /*
         * Determine whether the payment was refunded
         */

        $isRefunded = (
            $paymentMethod === 'stripe' &&
            $paymentStatus === 'refunded'
        );

        /*
         * Email subject and message
         */

        if ($isRefunded) {

            $subject = "Order Cancelled & Payment Refunded - " . $orderNumber;

            $heading = "Your Order Has Been Cancelled";

            $message = "
            Your order has been cancelled successfully.
            Since this order was paid through Stripe,
            your payment has also been refunded.
        ";

            $paymentMessage = "
            Your Stripe payment has been refunded.
            The time required for the refund to appear in
            your account may depend on your bank or card provider.
        ";

        } else {

            $subject = "Your Order Has Been Cancelled - " . $orderNumber;

            $heading = "Your Order Has Been Cancelled";

            $message = "
            Your order has been cancelled successfully.
        ";

            $paymentMessage = "
            No online payment refund is applicable to this order.
        ";
        }


        /*
         * Email body
         */

        $body = '
    <!DOCTYPE html>

    <html>

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >

        <title>
            ' . htmlspecialchars($subject) . '
        </title>

    </head>


    <body style="
        margin:0;
        padding:0;
        background-color:#f4f6f8;
        font-family:Arial, Helvetica, sans-serif;
    ">


        <div style="
            width:100%;
            padding:40px 15px;
            box-sizing:border-box;
        ">


            <div style="
                max-width:600px;
                margin:0 auto;
                background:#ffffff;
                border-radius:10px;
                overflow:hidden;
                box-shadow:0 3px 15px rgba(0,0,0,0.08);
            ">


                <!-- HEADER -->

                <div style="
                    background:#111827;
                    padding:25px;
                    text-align:center;
                ">

                    <h1 style="
                        margin:0;
                        color:#ffffff;
                        font-size:28px;
                        letter-spacing:1px;
                    ">
                        ElectroCart
                    </h1>


                    <p style="
                        margin:8px 0 0;
                        color:#d1d5db;
                        font-size:14px;
                    ">
                        Electronics at Your Fingertips
                    </p>

                </div>



                <!-- CONTENT -->

                <div style="
                    padding:35px 30px;
                ">


                    <h2 style="
                        margin:0 0 20px;
                        color:#111827;
                        font-size:24px;
                    ">
                        ' . htmlspecialchars($heading) . '
                    </h2>


                    <p style="
                        margin:0 0 15px;
                        color:#374151;
                        font-size:16px;
                        line-height:1.6;
                    ">

                        Hello
                        <strong>
                            ' . htmlspecialchars($toName) . '
                        </strong>,

                    </p>


                    <p style="
                        margin:0 0 20px;
                        color:#4b5563;
                        font-size:15px;
                        line-height:1.7;
                    ">

                        ' . nl2br(htmlspecialchars($message)) . '

                    </p>



                    <!-- ORDER INFORMATION -->

                    <div style="
                        background:#f9fafb;
                        border:1px solid #e5e7eb;
                        border-radius:8px;
                        padding:20px;
                        margin-bottom:25px;
                    ">


                        <h3 style="
                            margin:0 0 15px;
                            color:#111827;
                            font-size:17px;
                        ">

                            Order Information

                        </h3>



                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">

                            <strong>
                                Order Number:
                            </strong>

                            ' . htmlspecialchars($orderNumber) . '

                        </p>



                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">

                            <strong>
                                Order Total:
                            </strong>

                            PKR ' . number_format($total, 2) . '

                        </p>



                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">

                            <strong>
                                Payment Method:
                            </strong>

                            ' . htmlspecialchars(strtoupper($paymentMethod)) . '

                        </p>



                        <p style="
                            margin:8px 0;
                            color:#4b5563;
                            font-size:14px;
                        ">

                            <strong>
                                Order Status:
                            </strong>

                            Cancelled

                        </p>


                    </div>



                    <!-- REFUND INFORMATION -->

                    <div style="
                        background:#f3f4f6;
                        border-left:4px solid #374151;
                        padding:15px;
                        margin-bottom:25px;
                    ">


                        <p style="
                            margin:0;
                            color:#374151;
                            font-size:14px;
                            line-height:1.6;
                        ">

                            ' . nl2br(htmlspecialchars($paymentMessage)) . '

                        </p>


                    </div>



                    <p style="
                        margin:0;
                        color:#4b5563;
                        font-size:14px;
                        line-height:1.6;
                    ">

                        Thank you for shopping with
                        <strong>ElectroCart</strong>.

                    </p>


                </div>



                <!-- FOOTER -->

                <div style="
                    background:#f9fafb;
                    border-top:1px solid #e5e7eb;
                    padding:20px;
                    text-align:center;
                ">


                    <p style="
                        margin:0;
                        color:#6b7280;
                        font-size:13px;
                    ">

                        This is an automated email from ElectroCart.

                    </p>


                    <p style="
                        margin:8px 0 0;
                        color:#9ca3af;
                        font-size:12px;
                    ">

                        Please do not reply directly to this email.

                    </p>


                </div>


            </div>

        </div>

    </body>

    </html>
    ';


        return self::send(
            $toEmail,
            $toName,
            $subject,
            $body
        );
    }
}