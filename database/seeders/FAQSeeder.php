<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('f_a_q_s')->insert([
            [
                'question' => "How do I know if this supplement is right for me?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Supplement",
                'status' => 'active',
            ],
            [
                'question' => "How long does it take to see results?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Supplement",
                'status' => 'active',
            ],[
                'question' => "Can I take this supplement with my current medications?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Supplement",
                'status' => 'active',
            ],[
                'question' => "Is this supplement safe for long-term use?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Supplement",
                'status' => 'active',
            ],[
                'question' => "How should I store this supplement?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Supplement",
                'status' => 'active',
            ],


            //others faqs
            //-Placing an order
            [
                'question' => "How do I place an order?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Placing an order",
                'status' => 'active',
            ],
            [
                'question' => "Can I place an order over the phone?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Placing an order",
                'status' => 'active',
            ],[
                'question' => "What payment methods do you accept?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Placing an order",
                'status' => 'active',
            ],[
                'question' => "Are the medications you prescribe genuine?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Placing an order",
                'status' => 'active',
            ],

            //-Delivery
            [
                'question' => "What delivery options do you offer?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ],
            [
                'question' => "How long will my delivery take?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ],[
                'question' => "How much does delivery cost?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ],[
                'question' => "Can I track my delivery?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ], [
                'question' => "What should I do if my delivery is delayed?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ],[
                'question' => "Can I change my delivery address after placing an order?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "Delivery",
                'status' => 'active',
            ],

            //-About myhealthneeds
            [
                'question' => "What is MyHealthNeeds?",
                'answer' => "MyHealthNeeds is an online platform providing healthcare products, information, and resources to help users manage their health conditions. We offer products and treatments tailored to support a range of health needs.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ],
            [
                'question' => "What services does MyHealthNeeds offer?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ],[
                'question' => "Are the products from MyHealthNeeds safe?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ],[
                'question' => "Do I need a prescription to purchase products from MyHealthNeeds ?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ], [
                'question' => "How long does delivery take with MyHealthNeeds?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ],[
                'question' => " Can I return a product purchased from MyHealthNeeds?",
                'answer' => "Our healthcare professionals can help assess your individual needs. You may also consult your doctor to see if this supplement aligns with your current health goals.",
                'type' => "About myhealthneeds",
                'status' => 'active',
            ],
        ]);
    }
}
