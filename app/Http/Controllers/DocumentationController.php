<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentationController extends Controller
{
    public function show($document)
    {
        $docContent = $this->getDocContent($document);

        if (!$docContent) {
            abort(404);
        }

        $pdf = Pdf::loadView('docs.template', [
            'title' => $docContent['title'],
            'content' => $docContent['content'],
            'date' => date('d F Y')
        ]);

        return $pdf->stream($document . '.pdf');
    }

    private function getDocContent($document)
    {
        $docs = [
            'getting-started' => [
                'title' => 'Getting Started Guide',
                'content' => '
                    <h2>Welcome to Oboun ERP</h2>
                    <p>Oboun ERP is a comprehensive management system designed for pharmacies and retail businesses. This guide will help you get started with the basic features of the system.</p>
                    
                    <h3>1. Navigating the Dashboard</h3>
                    <p>The dashboard provides a real-time overview of your business performance, including daily sales, inventory levels, and upcoming tasks.</p>
                    
                    <h3>2. Managing Products</h3>
                    <p>Go to the Products section to add, edit, or remove items from your inventory. You can track stock levels, set prices, and manage categories.</p>
                    
                    <h3>3. Processing Sales</h3>
                    <p>Use the POS (Point of Sale) system to process customer transactions quickly and efficiently. You can search for products, apply discounts, and issue receipts.</p>
                    
                    <h3>4. Generating Reports</h3>
                    <p>Access the Reports section to view detailed analytics on sales, inventory movements, and financial performance over specific periods.</p>
                '
            ],
            'pos-checkout' => [
                'title' => 'POS & Checkout Basics',
                'content' => '
                    <h2>POS & Checkout Basics</h2>
                    <p>Master the Point of Sale system to streamline your checkout process.</p>
                    
                    <h3>Keyboard Shortcuts</h3>
                    <ul>
                        <li><strong>F1:</strong> Search Products</li>
                        <li><strong>F2:</strong> Select Customer</li>
                        <li><strong>F8:</strong> Payment / Checkout</li>
                        <li><strong>Cmd/Ctrl + H:</strong> Hold Order</li>
                    </ul>
                    
                    <h3>Processing a Sale</h3>
                    <ol>
                        <li>Search for a product using the search bar or barcode scanner.</li>
                        <li>Adjust quantities if necessary.</li>
                        <li>Select a customer to apply loyalty points or professional discounts.</li>
                        <li>Proceed to checkout and select the payment method (Cash, QR, or Credit Card).</li>
                        <li>Print the receipt for the customer.</li>
                    </ol>
                '
            ],
            'inventory' => [
                'title' => 'Inventory Management',
                'content' => '
                    <h2>Inventory Management</h2>
                    <p>Keep your stock levels optimized and organized.</p>
                    
                    <h3>Adding Stock</h3>
                    <p>Use the "Goods Received" module to record new stock arrivals. Ensure you verify the quantities against the supplier invoice.</p>
                    
                    <h3>Stock Adjustments</h3>
                    <p>If there are discrepancies due to damage or expiration, use the "Stock Adjustment" tool to update the inventory counts.</p>
                    
                    <h3>Managing Product Lots</h3>
                    <p>For pharmaceutical products, tracking lots and expiration dates is critical. The system will alert you as products approach their expiry date.</p>
                '
            ],
            'loyalty' => [
                'title' => 'Customer Loyalty Program',
                'content' => '
                    <h2>Customer Loyalty Program</h2>
                    <p>Enhance customer retention with our loyalty management features.</p>
                    
                    <h3>Member Registration</h3>
                    <p>Register new customers with their contact details and professional status (e.g., student, healthcare worker) to apply specific pricing tiers.</p>
                    
                    <h3>Earning Points</h3>
                    <p>Customers earn points for every purchase made. The accrual rate can be configured in the system settings.</p>
                    
                    <h3>Redeeming Rewards</h3>
                    <p>Points can be redeemed for discounts or specific rewards at the point of checkout.</p>
                '
            ]
        ];

        return $docs[$document] ?? null;
    }
}
