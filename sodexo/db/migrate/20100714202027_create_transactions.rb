class CreateTransactions < ActiveRecord::Migration
  def self.up
    create_table :transactions do |t|
      t.date :trdate
      t.integer :tramount
      t.integer :trans_id
      t.string :trans_type

      t.timestamps
    end
  end

  def self.down
    drop_table :transactions
  end
end
