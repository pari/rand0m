class AddIsclientToUsers < ActiveRecord::Migration
  def self.up
    add_column :users, :isclient, :boolean
  end

  def self.down
    remove_column :users, :isclient
  end
end
