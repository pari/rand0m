# This file is auto-generated from the current state of the database. Instead of editing this file, 
# please use the migrations feature of Active Record to incrementally modify your database, and
# then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your database schema. If you need
# to create the application database on another system, you should be using db:schema:load, not running
# all the migrations from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 20100715070731) do

  create_table "buyers", :force => true do |t|
    t.string   "name"
    t.string   "address"
    t.string   "phone1"
    t.string   "phone2"
    t.boolean  "isActive"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "coupon_prices", :force => true do |t|
    t.integer  "price"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "denominations", :force => true do |t|
    t.integer  "ticketprice"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "tr_details", :force => true do |t|
    t.integer  "coupon_price_id"
    t.integer  "qty"
    t.integer  "transaction_id"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "transactions", :force => true do |t|
    t.date     "trdate"
    t.integer  "tramount"
    t.integer  "trans_id"
    t.string   "trans_type"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "vendors", :force => true do |t|
    t.string   "name"
    t.string   "address"
    t.string   "phone1"
    t.string   "phone2"
    t.boolean  "isActive"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

end